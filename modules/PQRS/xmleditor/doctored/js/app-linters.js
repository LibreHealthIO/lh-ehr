/*globals doctored, window, alert, Worker, console*/
(function(){
	"use strict";

    var get_worker = function(i){
        return {
          ready:  true,
          index: i,
          Worker: new Worker(window.doctored.base + "js/app-linter-worker.js")
        };
    };

    doctored.linters = {
        pool: [],
        pool_cursor: 0,
        config: {
            number_of_workers: 3, // the optimum MAY be:   number_of_workers = CPU_CORES - 1.
                                  // because we want one core on the UI/browser thread, and other cores available for linting.
                                  // BUT it's basically impossible to determine number of CPU cores via JavaScript,
                                  //   (e.g. see all the variation here http://www.reddit.com/r/programming/comments/1ezdnv/ )
                                  // AND whatever number we choose will be flawed because other system load will affect
                                  // the optimum number of workers.
                                  //   (e.g. see http://lists.whatwg.org/htdig.cgi/whatwg-whatwg.org/2009-November/024018.html
                                  //             http://lists.whatwg.org/pipermail/whatwg-whatwg.org/2009-November/024058.html )
                                  // So it's a bit arbitrary but 3 is chosen because of the popularity of Intel i5/i7 CPUs which
                                  // have 4 cores, and even Intel i3s have 2 cores with hyperthreading (2 cores pretending to be 4).
                                  // TODO: we could make this configurable

            when_all_workers_are_busy_retry_after_milliseconds: 100
        },
        lint: function(xml_string, path_to_schema, callback, context){
            var _this          = this,
				linters        = doctored.linters,
				initial_cursor = linters.pool_cursor,
				worker         = linters.pool[linters.pool_cursor];

            if(linters.config.number_of_workers === 0) return;
            
            while(worker.ready !== true){
                linters.pool_cursor = doctored.util.increment_but_wrap_at(linters.pool_cursor, linters.pool.length);
                worker = linters.pool[linters.pool_cursor];
                if(linters.pool_cursor === initial_cursor) { //then we've looped around, so we'll discard the current request
                    //console.log("Killing off old worker #" + worker.index + " and restarting it.");
                    worker.Worker.terminate(); //this is relative expensive for memory/CPU so we don't really want to have to do this. Perhaps it would be better to wait?
                    worker = get_worker(worker.index);
                }
            }
            
            linters.pool_cursor = doctored.util.increment_but_wrap_at(linters.pool_cursor, linters.pool.length);

            //if(console && console.log) console.log("Gave job to worker #" + worker.index);
            worker.callback = callback;
            worker.context  = context;
            worker.ready    = false;
            worker.Worker.postMessage({
                "xml":        xml_string,
                "index":      worker.index,
                "schema_url": path_to_schema
            });
            return true;
        },
        Worker_response: function(event){
            var linters = doctored.linters,
                worker;
 
            if(!event || !event.data || event.data.index === undefined || event.data.index === -1) {
                return console.log("Unidentified worker response ", event);
            }
            worker = linters.pool[event.data.index];
            worker.ready = true; //worker is now ready for more work
            if(!event.data || !event.data.type) {
                return console.log("Unknown worker response of ", event);
            }
            switch(event.data.type){
                case "debug":
                    return console.log("DEBUG: Worker#" + event.data.index + " said: " + event.data.message);
                case "result":
                    if(worker.context) {
                        return worker.callback.bind(worker.context)(event.data.result);
                    }
                    return worker.callback(event.data.result);
                default:
                    return console.log("Unknown worker response of ", event);
            }
        },
        init: function(){
            var i    = 0,
                linters = doctored.linters;

            if(typeof window.Worker !== "function") {
                alert("Doctored.js schema validation requires a browser that supports Web Workers.");
                linters.config.number_of_workers = 0;
            }

            for(i = 0; i < linters.config.number_of_workers; i++){
               linters.pool.push(get_worker(i));
               linters.pool[linters.pool.length - 1].Worker.onmessage = linters.Worker_response;
            }
        }
    };

    doctored.linters.init();
}());
