/**
 * @file src/better-timeinput-polyfill.js
 * @version 1.1.1 2013-12-27T18:46:50
 * @overview input[type=time] polyfill for better-dom
 * @copyright Maksim Chemerisuk 2013
 * @license MIT
 * @see https://github.com/chemerisuk/better-timeinput-polyfill
 */
(function(DOM, COMPONENT_CLASS) {
    "use strict";

    if ("orientation" in window) return; // skip mobile/tablet browsers

    // polyfill timeinput for desktop browsers
    var htmlEl = DOM.find("html"),
        timeparts = function(str) {
            str = str.split(":");

            if (str.length === 2) {
                str[0] = parseFloat(str[0]);
                str[1] = parseFloat(str[1]);
            } else {
                str = [];
            }

            return str;
        },
        zeropad = function(value) { return ("00" + value).slice(-2) },
        ampm = function(pos, neg) { return htmlEl.get("lang") === "en-US" ? pos : neg },
        formatISOTime = function(hours, minutes, ampm) {
            return zeropad(ampm === "PM" ? hours + 12 : hours) + ":" + zeropad(minutes);
        };

    DOM.extend("input[type=time]", {
        constructor: function() {
            var timeinput = DOM.create("input[type=hidden name=${name}]", {name: this.get("name")}),
                ampmspan = DOM.create("span.${c}-meridian>(select>option>{AM}^option>{PM})+span>{AM}", {c: COMPONENT_CLASS}),
                ampmselect = ampmspan.child(0);

            this
                // drop native implementation and clear name attribute
                .set({type: "text", maxlength: 5, name: null})
                .addClass(COMPONENT_CLASS)
                .on("change", this.onChange.bind(this, timeinput, ampmselect))
                .on("keydown", this.onKeydown, ["which", "shiftKey"])
                .after(ampmspan, timeinput);

            ampmselect.on("change", this.onMeridianChange.bind(this, timeinput, ampmselect));
            // update value correctly on form reset
            this.parent("form").on("reset", this.onFormReset.bind(this, timeinput, ampmselect));
            // patch set method to update visible input as well
            timeinput.set = this.onValueChanged.bind(this, timeinput.set, timeinput, ampmselect);
            // update hidden input value and refresh all visible controls
            timeinput.set(this.get()).data("defaultValue", timeinput.get());
            // update default values to be formatted
            this.set("defaultValue", this.get());
            ampmselect.next().data("defaultValue", ampmselect.get());

            if (this.matches(":focus")) timeinput.fire("focus");
        },
        onValueChanged: function(setter, timeinput, ampmselect) {
            var parts, hours, minutes;

            setter.apply(timeinput, Array.prototype.slice.call(arguments, 3));

            if (arguments.length === 4) {
                parts = timeparts(timeinput.get());
                hours = parts[0];
                minutes = parts[1];
                // select appropriate AM/PM
                ampmselect.child((hours -= 12) > 0 ? 1 : Math.min(hours += 12, 0)).set("selected", true);
                // update displayed AM/PM
                ampmselect.next().set(ampmselect.get());
                // update visible input value, need to add zero padding to minutes
                this.set(hours < ampm(13, 24) && minutes < 60 ? hours + ":" + zeropad(minutes) : "");
            }

            return timeinput;
        },
        onKeydown: function(which, shiftKey) {
            return which === 186 && shiftKey || which < 58;
        },
        onChange: function(timeinput, ampmselect) {
            var parts = timeparts(this.get()),
                hours = parts[0],
                minutes = parts[1],
                value = "";

            if (hours < ampm(13, 24) && minutes < 60) {
                // refresh hidden input with new value
                value = formatISOTime(hours, minutes, ampmselect.get());
            } else if (parts.length === 2) {
                // restore previous valid value
                value = timeinput.get();
            }

            timeinput.set(value);
        },
        onMeridianChange: function(timeinput, ampmselect) {
            // update displayed AM/PM
            ampmselect.next().set(ampmselect.get());
            // adjust time in hidden input
            timeinput.set(function(el) {
                var parts = timeparts(el.get()),
                    hours = parts[0],
                    minutes = parts[1];

                if (ampmselect.get() === "AM") hours -= 12;

                return formatISOTime(hours, minutes, ampmselect.get());
            });
        },
        onFormReset: function(timeinput, ampmselect) {
            timeinput.set(timeinput.data("defaultValue"));
            ampmselect.next().set(ampmselect.data("defaultValue"));
        }
    });
}(window.DOM, "better-timeinput"));
