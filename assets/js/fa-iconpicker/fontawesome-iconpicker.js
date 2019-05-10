(function(e) {
    if (typeof define === "function" && define.amd) {
        define([ "jquery" ], e);
    } else {
        e(jQuery);
    }
})(function(V) {
    V.ui = V.ui || {};
    var e = V.ui.version = "1.12.1";
    (function() {
        var r, y = Math.max, x = Math.abs, s = /left|center|right/, i = /top|center|bottom/, f = /[\+\-]\d+(\.[\d]+)?%?/, l = /^\w+/, c = /%$/, a = V.fn.pos;
        function q(e, a, t) {
            return [ parseFloat(e[0]) * (c.test(e[0]) ? a / 100 : 1), parseFloat(e[1]) * (c.test(e[1]) ? t / 100 : 1) ];
        }
        function C(e, a) {
            return parseInt(V.css(e, a), 10) || 0;
        }
        function t(e) {
            var a = e[0];
            if (a.nodeType === 9) {
                return {
                    width: e.width(),
                    height: e.height(),
                    offset: {
                        top: 0,
                        left: 0
                    }
                };
            }
            if (V.isWindow(a)) {
                return {
                    width: e.width(),
                    height: e.height(),
                    offset: {
                        top: e.scrollTop(),
                        left: e.scrollLeft()
                    }
                };
            }
            if (a.preventDefault) {
                return {
                    width: 0,
                    height: 0,
                    offset: {
                        top: a.pageY,
                        left: a.pageX
                    }
                };
            }
            return {
                width: e.outerWidth(),
                height: e.outerHeight(),
                offset: e.offset()
            };
        }
        V.pos = {
            scrollbarWidth: function() {
                if (r !== undefined) {
                    return r;
                }
                var e, a, t = V("<div " + "style='display:block;position:absolute;width:50px;height:50px;overflow:hidden;'>" + "<div style='height:100px;width:auto;'></div></div>"), s = t.children()[0];
                V("body").append(t);
                e = s.offsetWidth;
                t.css("overflow", "scroll");
                a = s.offsetWidth;
                if (e === a) {
                    a = t[0].clientWidth;
                }
                t.remove();
                return r = e - a;
            },
            getScrollInfo: function(e) {
                var a = e.isWindow || e.isDocument ? "" : e.element.css("overflow-x"), t = e.isWindow || e.isDocument ? "" : e.element.css("overflow-y"), s = a === "scroll" || a === "auto" && e.width < e.element[0].scrollWidth, r = t === "scroll" || t === "auto" && e.height < e.element[0].scrollHeight;
                return {
                    width: r ? V.pos.scrollbarWidth() : 0,
                    height: s ? V.pos.scrollbarWidth() : 0
                };
            },
            getWithinInfo: function(e) {
                var a = V(e || window), t = V.isWindow(a[0]), s = !!a[0] && a[0].nodeType === 9, r = !t && !s;
                return {
                    element: a,
                    isWindow: t,
                    isDocument: s,
                    offset: r ? V(e).offset() : {
                        left: 0,
                        top: 0
                    },
                    scrollLeft: a.scrollLeft(),
                    scrollTop: a.scrollTop(),
                    width: a.outerWidth(),
                    height: a.outerHeight()
                };
            }
        };
        V.fn.pos = function(h) {
            if (!h || !h.of) {
                return a.apply(this, arguments);
            }
            h = V.extend({}, h);
            var m, p, d, T, u, e, b = V(h.of), g = V.pos.getWithinInfo(h.within), k = V.pos.getScrollInfo(g), w = (h.collision || "flip").split(" "), v = {};
            e = t(b);
            if (b[0].preventDefault) {
                h.at = "left top";
            }
            p = e.width;
            d = e.height;
            T = e.offset;
            u = V.extend({}, T);
            V.each([ "my", "at" ], function() {
                var e = (h[this] || "").split(" "), a, t;
                if (e.length === 1) {
                    e = s.test(e[0]) ? e.concat([ "center" ]) : i.test(e[0]) ? [ "center" ].concat(e) : [ "center", "center" ];
                }
                e[0] = s.test(e[0]) ? e[0] : "center";
                e[1] = i.test(e[1]) ? e[1] : "center";
                a = f.exec(e[0]);
                t = f.exec(e[1]);
                v[this] = [ a ? a[0] : 0, t ? t[0] : 0 ];
                h[this] = [ l.exec(e[0])[0], l.exec(e[1])[0] ];
            });
            if (w.length === 1) {
                w[1] = w[0];
            }
            if (h.at[0] === "right") {
                u.left += p;
            } else if (h.at[0] === "center") {
                u.left += p / 2;
            }
            if (h.at[1] === "bottom") {
                u.top += d;
            } else if (h.at[1] === "center") {
                u.top += d / 2;
            }
            m = q(v.at, p, d);
            u.left += m[0];
            u.top += m[1];
            return this.each(function() {
                var t, e, f = V(this), l = f.outerWidth(), c = f.outerHeight(), a = C(this, "marginLeft"), s = C(this, "marginTop"), r = l + a + C(this, "marginRight") + k.width, i = c + s + C(this, "marginBottom") + k.height, o = V.extend({}, u), n = q(v.my, f.outerWidth(), f.outerHeight());
                if (h.my[0] === "right") {
                    o.left -= l;
                } else if (h.my[0] === "center") {
                    o.left -= l / 2;
                }
                if (h.my[1] === "bottom") {
                    o.top -= c;
                } else if (h.my[1] === "center") {
                    o.top -= c / 2;
                }
                o.left += n[0];
                o.top += n[1];
                t = {
                    marginLeft: a,
                    marginTop: s
                };
                V.each([ "left", "top" ], function(e, a) {
                    if (V.ui.pos[w[e]]) {
                        V.ui.pos[w[e]][a](o, {
                            targetWidth: p,
                            targetHeight: d,
                            elemWidth: l,
                            elemHeight: c,
                            collisionPosition: t,
                            collisionWidth: r,
                            collisionHeight: i,
                            offset: [ m[0] + n[0], m[1] + n[1] ],
                            my: h.my,
                            at: h.at,
                            within: g,
                            elem: f
                        });
                    }
                });
                if (h.using) {
                    e = function(e) {
                        var a = T.left - o.left, t = a + p - l, s = T.top - o.top, r = s + d - c, i = {
                            target: {
                                element: b,
                                left: T.left,
                                top: T.top,
                                width: p,
                                height: d
                            },
                            element: {
                                element: f,
                                left: o.left,
                                top: o.top,
                                width: l,
                                height: c
                            },
                            horizontal: t < 0 ? "left" : a > 0 ? "right" : "center",
                            vertical: r < 0 ? "top" : s > 0 ? "bottom" : "middle"
                        };
                        if (p < l && x(a + t) < p) {
                            i.horizontal = "center";
                        }
                        if (d < c && x(s + r) < d) {
                            i.vertical = "middle";
                        }
                        if (y(x(a), x(t)) > y(x(s), x(r))) {
                            i.important = "horizontal";
                        } else {
                            i.important = "vertical";
                        }
                        h.using.call(this, e, i);
                    };
                }
                f.offset(V.extend(o, {
                    using: e
                }));
            });
        };
        V.ui.pos = {
            _trigger: function(e, a, t, s) {
                if (a.elem) {
                    a.elem.trigger({
                        type: t,
                        position: e,
                        positionData: a,
                        triggered: s
                    });
                }
            },
            fit: {
                left: function(e, a) {
                    V.ui.pos._trigger(e, a, "posCollide", "fitLeft");
                    var t = a.within, s = t.isWindow ? t.scrollLeft : t.offset.left, r = t.width, i = e.left - a.collisionPosition.marginLeft, f = s - i, l = i + a.collisionWidth - r - s, c;
                    if (a.collisionWidth > r) {
                        if (f > 0 && l <= 0) {
                            c = e.left + f + a.collisionWidth - r - s;
                            e.left += f - c;
                        } else if (l > 0 && f <= 0) {
                            e.left = s;
                        } else {
                            if (f > l) {
                                e.left = s + r - a.collisionWidth;
                            } else {
                                e.left = s;
                            }
                        }
                    } else if (f > 0) {
                        e.left += f;
                    } else if (l > 0) {
                        e.left -= l;
                    } else {
                        e.left = y(e.left - i, e.left);
                    }
                    V.ui.pos._trigger(e, a, "posCollided", "fitLeft");
                },
                top: function(e, a) {
                    V.ui.pos._trigger(e, a, "posCollide", "fitTop");
                    var t = a.within, s = t.isWindow ? t.scrollTop : t.offset.top, r = a.within.height, i = e.top - a.collisionPosition.marginTop, f = s - i, l = i + a.collisionHeight - r - s, c;
                    if (a.collisionHeight > r) {
                        if (f > 0 && l <= 0) {
                            c = e.top + f + a.collisionHeight - r - s;
                            e.top += f - c;
                        } else if (l > 0 && f <= 0) {
                            e.top = s;
                        } else {
                            if (f > l) {
                                e.top = s + r - a.collisionHeight;
                            } else {
                                e.top = s;
                            }
                        }
                    } else if (f > 0) {
                        e.top += f;
                    } else if (l > 0) {
                        e.top -= l;
                    } else {
                        e.top = y(e.top - i, e.top);
                    }
                    V.ui.pos._trigger(e, a, "posCollided", "fitTop");
                }
            },
            flip: {
                left: function(e, a) {
                    V.ui.pos._trigger(e, a, "posCollide", "flipLeft");
                    var t = a.within, s = t.offset.left + t.scrollLeft, r = t.width, i = t.isWindow ? t.scrollLeft : t.offset.left, f = e.left - a.collisionPosition.marginLeft, l = f - i, c = f + a.collisionWidth - r - i, o = a.my[0] === "left" ? -a.elemWidth : a.my[0] === "right" ? a.elemWidth : 0, n = a.at[0] === "left" ? a.targetWidth : a.at[0] === "right" ? -a.targetWidth : 0, h = -2 * a.offset[0], m, p;
                    if (l < 0) {
                        m = e.left + o + n + h + a.collisionWidth - r - s;
                        if (m < 0 || m < x(l)) {
                            e.left += o + n + h;
                        }
                    } else if (c > 0) {
                        p = e.left - a.collisionPosition.marginLeft + o + n + h - i;
                        if (p > 0 || x(p) < c) {
                            e.left += o + n + h;
                        }
                    }
                    V.ui.pos._trigger(e, a, "posCollided", "flipLeft");
                },
                top: function(e, a) {
                    V.ui.pos._trigger(e, a, "posCollide", "flipTop");
                    var t = a.within, s = t.offset.top + t.scrollTop, r = t.height, i = t.isWindow ? t.scrollTop : t.offset.top, f = e.top - a.collisionPosition.marginTop, l = f - i, c = f + a.collisionHeight - r - i, o = a.my[1] === "top", n = o ? -a.elemHeight : a.my[1] === "bottom" ? a.elemHeight : 0, h = a.at[1] === "top" ? a.targetHeight : a.at[1] === "bottom" ? -a.targetHeight : 0, m = -2 * a.offset[1], p, d;
                    if (l < 0) {
                        d = e.top + n + h + m + a.collisionHeight - r - s;
                        if (d < 0 || d < x(l)) {
                            e.top += n + h + m;
                        }
                    } else if (c > 0) {
                        p = e.top - a.collisionPosition.marginTop + n + h + m - i;
                        if (p > 0 || x(p) < c) {
                            e.top += n + h + m;
                        }
                    }
                    V.ui.pos._trigger(e, a, "posCollided", "flipTop");
                }
            },
            flipfit: {
                left: function() {
                    V.ui.pos.flip.left.apply(this, arguments);
                    V.ui.pos.fit.left.apply(this, arguments);
                },
                top: function() {
                    V.ui.pos.flip.top.apply(this, arguments);
                    V.ui.pos.fit.top.apply(this, arguments);
                }
            }
        };
        (function() {
            var e, a, t, s, r, i = document.getElementsByTagName("body")[0], f = document.createElement("div");
            e = document.createElement(i ? "div" : "body");
            t = {
                visibility: "hidden",
                width: 0,
                height: 0,
                border: 0,
                margin: 0,
                background: "none"
            };
            if (i) {
                V.extend(t, {
                    position: "absolute",
                    left: "-1000px",
                    top: "-1000px"
                });
            }
            for (r in t) {
                e.style[r] = t[r];
            }
            e.appendChild(f);
            a = i || document.documentElement;
            a.insertBefore(e, a.firstChild);
            f.style.cssText = "position: absolute; left: 10.7432222px;";
            s = V(f).offset().left;
            V.support.offsetFractions = s > 10 && s < 11;
            e.innerHTML = "";
            a.removeChild(e);
        })();
    })();
    var a = V.ui.position;
});

(function(e) {
    "use strict";
    if (typeof define === "function" && define.amd) {
        define([ "jquery" ], e);
    } else if (window.jQuery && !window.jQuery.fn.iconpicker) {
        e(window.jQuery);
    }
})(function(f) {
    "use strict";
    var l = {
        isEmpty: function(e) {
            return e === false || e === "" || e === null || e === undefined;
        },
        isEmptyObject: function(e) {
            return this.isEmpty(e) === true || e.length === 0;
        },
        isElement: function(e) {
            return f(e).length > 0;
        },
        isString: function(e) {
            return typeof e === "string" || e instanceof String;
        },
        isArray: function(e) {
            return f.isArray(e);
        },
        inArray: function(e, a) {
            return f.inArray(e, a) !== -1;
        },
        throwError: function(e) {
            throw "Font Awesome Icon Picker Exception: " + e;
        }
    };
    var t = function(e, a) {
        this._id = t._idCounter++;
        this.element = f(e).addClass("iconpicker-element");
        this._trigger("iconpickerCreate", {
            iconpickerValue: this.iconpickerValue
        });
        this.options = f.extend({}, t.defaultOptions, this.element.data(), a);
        this.options.templates = f.extend({}, t.defaultOptions.templates, this.options.templates);
        this.options.originalPlacement = this.options.placement;
        this.container = l.isElement(this.options.container) ? f(this.options.container) : false;
        if (this.container === false) {
            if (this.element.is(".dropdown-toggle")) {
                this.container = f("~ .dropdown-menu:first", this.element);
            } else {
                this.container = this.element.is("input,textarea,button,.btn") ? this.element.parent() : this.element;
            }
        }
        this.container.addClass("iconpicker-container");
        if (this.isDropdownMenu()) {
            this.options.placement = "inline";
        }
        this.input = this.element.is("input,textarea") ? this.element.addClass("iconpicker-input") : false;
        if (this.input === false) {
            this.input = this.container.find(this.options.input);
            if (!this.input.is("input,textarea")) {
                this.input = false;
            }
        }
        this.component = this.isDropdownMenu() ? this.container.parent().find(this.options.component) : this.container.find(this.options.component);
        if (this.component.length === 0) {
            this.component = false;
        } else {
            this.component.find("i").addClass("iconpicker-component");
        }
        this._createPopover();
        this._createIconpicker();
        if (this.getAcceptButton().length === 0) {
            this.options.mustAccept = false;
        }
        if (this.isInputGroup()) {
            this.container.parent().append(this.popover);
        } else {
            this.container.append(this.popover);
        }
        this._bindElementEvents();
        this._bindWindowEvents();
        this.update(this.options.selected);
        if (this.isInline()) {
            this.show();
        }
        this._trigger("iconpickerCreated", {
            iconpickerValue: this.iconpickerValue
        });
    };
    t._idCounter = 0;
    t.defaultOptions = {
        title: false,
        selected: false,
        defaultValue: false,
        placement: "bottom",
        collision: "none",
        animation: true,
        hideOnSelect: false,
        showFooter: false,
        searchInFooter: false,
        mustAccept: false,
        selectedCustomClass: "bg-primary",
        icons: [],
        fullClassFormatter: function(e) {
            return e;
        },
        input: "input,.iconpicker-input",
        inputSearch: false,
        container: false,
        component: ".input-group-addon,.iconpicker-component",
        templates: {
            popover: '<div class="iconpicker-popover popover"><div class="arrow"></div>' + '<div class="popover-title"></div><div class="popover-content"></div></div>',
            footer: '<div class="popover-footer"></div>',
            buttons: '<button class="iconpicker-btn iconpicker-btn-cancel btn btn-default btn-sm">Cancel</button>' + ' <button class="iconpicker-btn iconpicker-btn-accept btn btn-primary btn-sm">Accept</button>',
            search: '<input type="search" class="form-control iconpicker-search" placeholder="Type to filter" />',
            iconpicker: '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
            iconpickerItem: '<a role="button" href="#" class="iconpicker-item"><i></i></a>'
        }
    };
    t.batch = function(e, a) {
        var t = Array.prototype.slice.call(arguments, 2);
        return f(e).each(function() {
            var e = f(this).data("iconpicker");
            if (!!e) {
                e[a].apply(e, t);
            }
        });
    };
    t.prototype = {
        constructor: t,
        options: {},
        _id: 0,
        _trigger: function(e, a) {
            a = a || {};
            this.element.trigger(f.extend({
                type: e,
                iconpickerInstance: this
            }, a));
        },
        _createPopover: function() {
            this.popover = f(this.options.templates.popover);
            var e = this.popover.find(".popover-title");
            if (!!this.options.title) {
                e.append(f('<div class="popover-title-text">' + this.options.title + "</div>"));
            }
            if (this.hasSeparatedSearchInput() && !this.options.searchInFooter) {
                e.append(this.options.templates.search);
            } else if (!this.options.title) {
                e.remove();
            }
            if (this.options.showFooter && !l.isEmpty(this.options.templates.footer)) {
                var a = f(this.options.templates.footer);
                if (this.hasSeparatedSearchInput() && this.options.searchInFooter) {
                    a.append(f(this.options.templates.search));
                }
                if (!l.isEmpty(this.options.templates.buttons)) {
                    a.append(f(this.options.templates.buttons));
                }
                this.popover.append(a);
            }
            if (this.options.animation === true) {
                this.popover.addClass("fade");
            }
            return this.popover;
        },
        _createIconpicker: function() {
            var t = this;
            this.iconpicker = f(this.options.templates.iconpicker);
            var e = function(e) {
                var a = f(this);
                if (a.is("i")) {
                    a = a.parent();
                }
                t._trigger("iconpickerSelect", {
                    iconpickerItem: a,
                    iconpickerValue: t.iconpickerValue
                });
                if (t.options.mustAccept === false) {
                    t.update(a.data("iconpickerValue"));
                    t._trigger("iconpickerSelected", {
                        iconpickerItem: this,
                        iconpickerValue: t.iconpickerValue
                    });
                } else {
                    t.update(a.data("iconpickerValue"), true);
                }
                if (t.options.hideOnSelect && t.options.mustAccept === false) {
                    t.hide();
                }
            };
            for (var a in this.options.icons) {
                if (typeof this.options.icons[a].title === "string") {
                    var s = f(this.options.templates.iconpickerItem);
                    s.find("i").addClass(this.options.fullClassFormatter(this.options.icons[a].title));
                    s.data("iconpickerValue", this.options.icons[a].title).on("click.iconpicker", e);
                    this.iconpicker.find(".iconpicker-items").append(s.attr("title", "." + this.options.icons[a].title));
                    if (this.options.icons[a].searchTerms.length > 0) {
                        var r = "";
                        for (var i = 0; i < this.options.icons[a].searchTerms.length; i++) {
                            r = r + this.options.icons[a].searchTerms[i] + " ";
                        }
                        this.iconpicker.find(".iconpicker-items").append(s.attr("data-search-terms", r));
                    }
                }
            }
            this.popover.find(".popover-content").append(this.iconpicker);
            return this.iconpicker;
        },
        _isEventInsideIconpicker: function(e) {
            var a = f(e.target);
            if ((!a.hasClass("iconpicker-element") || a.hasClass("iconpicker-element") && !a.is(this.element)) && a.parents(".iconpicker-popover").length === 0) {
                return false;
            }
            return true;
        },
        _bindElementEvents: function() {
            var a = this;
            this.getSearchInput().on("keyup.iconpicker", function() {
                a.filter(f(this).val().toLowerCase());
            });
            this.getAcceptButton().on("click.iconpicker", function() {
                var e = a.iconpicker.find(".iconpicker-selected").get(0);
                a.update(a.iconpickerValue);
                a._trigger("iconpickerSelected", {
                    iconpickerItem: e,
                    iconpickerValue: a.iconpickerValue
                });
                if (!a.isInline()) {
                    a.hide();
                }
            });
            this.getCancelButton().on("click.iconpicker", function() {
                if (!a.isInline()) {
                    a.hide();
                }
            });
            this.element.on("focus.iconpicker", function(e) {
                a.show();
                e.stopPropagation();
            });
            if (this.hasComponent()) {
                this.component.on("click.iconpicker", function() {
                    a.toggle();
                });
            }
            if (this.hasInput()) {
                this.input.on("keyup.iconpicker", function(e) {
                    if (!l.inArray(e.keyCode, [ 38, 40, 37, 39, 16, 17, 18, 9, 8, 91, 93, 20, 46, 186, 190, 46, 78, 188, 44, 86 ])) {
                        a.update();
                    } else {
                        a._updateFormGroupStatus(a.getValid(this.value) !== false);
                    }
                    if (a.options.inputSearch === true) {
                        a.filter(f(this).val().toLowerCase());
                    }
                });
            }
        },
        _bindWindowEvents: function() {
            var e = f(window.document);
            var a = this;
            var t = ".iconpicker.inst" + this._id;
            f(window).on("resize.iconpicker" + t + " orientationchange.iconpicker" + t, function(e) {
                if (a.popover.hasClass("in")) {
                    a.updatePlacement();
                }
            });
            if (!a.isInline()) {
                e.on("mouseup" + t, function(e) {
                    if (!a._isEventInsideIconpicker(e) && !a.isInline()) {
                        a.hide();
                    }
                });
            }
        },
        _unbindElementEvents: function() {
            this.popover.off(".iconpicker");
            this.element.off(".iconpicker");
            if (this.hasInput()) {
                this.input.off(".iconpicker");
            }
            if (this.hasComponent()) {
                this.component.off(".iconpicker");
            }
            if (this.hasContainer()) {
                this.container.off(".iconpicker");
            }
        },
        _unbindWindowEvents: function() {
            f(window).off(".iconpicker.inst" + this._id);
            f(window.document).off(".iconpicker.inst" + this._id);
        },
        updatePlacement: function(e, a) {
            e = e || this.options.placement;
            this.options.placement = e;
            a = a || this.options.collision;
            a = a === true ? "flip" : a;
            var t = {
                at: "right bottom",
                my: "right top",
                of: this.hasInput() && !this.isInputGroup() ? this.input : this.container,
                collision: a === true ? "flip" : a,
                within: window
            };
            this.popover.removeClass("inline topLeftCorner topLeft top topRight topRightCorner " + "rightTop right rightBottom bottomRight bottomRightCorner " + "bottom bottomLeft bottomLeftCorner leftBottom left leftTop");
            if (typeof e === "object") {
                return this.popover.pos(f.extend({}, t, e));
            }
            switch (e) {
              case "inline":
                {
                    t = false;
                }
                break;

              case "topLeftCorner":
                {
                    t.my = "right bottom";
                    t.at = "left top";
                }
                break;

              case "topLeft":
                {
                    t.my = "left bottom";
                    t.at = "left top";
                }
                break;

              case "top":
                {
                    t.my = "center bottom";
                    t.at = "center top";
                }
                break;

              case "topRight":
                {
                    t.my = "right bottom";
                    t.at = "right top";
                }
                break;

              case "topRightCorner":
                {
                    t.my = "left bottom";
                    t.at = "right top";
                }
                break;

              case "rightTop":
                {
                    t.my = "left bottom";
                    t.at = "right center";
                }
                break;

              case "right":
                {
                    t.my = "left center";
                    t.at = "right center";
                }
                break;

              case "rightBottom":
                {
                    t.my = "left top";
                    t.at = "right center";
                }
                break;

              case "bottomRightCorner":
                {
                    t.my = "left top";
                    t.at = "right bottom";
                }
                break;

              case "bottomRight":
                {
                    t.my = "right top";
                    t.at = "right bottom";
                }
                break;

              case "bottom":
                {
                    t.my = "center top";
                    t.at = "center bottom";
                }
                break;

              case "bottomLeft":
                {
                    t.my = "left top";
                    t.at = "left bottom";
                }
                break;

              case "bottomLeftCorner":
                {
                    t.my = "right top";
                    t.at = "left bottom";
                }
                break;

              case "leftBottom":
                {
                    t.my = "right top";
                    t.at = "left center";
                }
                break;

              case "left":
                {
                    t.my = "right center";
                    t.at = "left center";
                }
                break;

              case "leftTop":
                {
                    t.my = "right bottom";
                    t.at = "left center";
                }
                break;

              default:
                {
                    return false;
                }
                break;
            }
            this.popover.css({
                display: this.options.placement === "inline" ? "" : "block"
            });
            if (t !== false) {
                this.popover.pos(t).css("maxWidth", f(window).width() - this.container.offset().left - 5);
            } else {
                this.popover.css({
                    top: "auto",
                    right: "auto",
                    bottom: "auto",
                    left: "auto",
                    maxWidth: "none"
                });
            }
            this.popover.addClass(this.options.placement);
            return true;
        },
        _updateComponents: function() {
            this.iconpicker.find(".iconpicker-item.iconpicker-selected").removeClass("iconpicker-selected " + this.options.selectedCustomClass);
            if (this.iconpickerValue) {
                this.iconpicker.find("." + this.options.fullClassFormatter(this.iconpickerValue).replace(/ /g, ".")).parent().addClass("iconpicker-selected " + this.options.selectedCustomClass);
            }
            if (this.hasComponent()) {
                var e = this.component.find("i");
                if (e.length > 0) {
                    e.attr("class", this.options.fullClassFormatter(this.iconpickerValue));
                } else {
                    this.component.html(this.getHtml());
                }
            }
        },
        _updateFormGroupStatus: function(e) {
            if (this.hasInput()) {
                if (e !== false) {
                    this.input.parents(".form-group:first").removeClass("has-error");
                } else {
                    this.input.parents(".form-group:first").addClass("has-error");
                }
                return true;
            }
            return false;
        },
        getValid: function(e) {
            if (!l.isString(e)) {
                e = "";
            }
            var a = e === "";
            e = f.trim(e);
            var t = false;
            for (var s = 0; s < this.options.icons.length; s++) {
                if (this.options.icons[s].title === e) {
                    t = true;
                    break;
                }
            }
            if (t || a) {
                return e;
            }
            return false;
        },
        setValue: function(e) {
            var a = this.getValid(e);
            if (a !== false) {
                this.iconpickerValue = a;
                this._trigger("iconpickerSetValue", {
                    iconpickerValue: a
                });
                return this.iconpickerValue;
            } else {
                this._trigger("iconpickerInvalid", {
                    iconpickerValue: e
                });
                return false;
            }
        },
        getHtml: function() {
            return '<i class="' + this.options.fullClassFormatter(this.iconpickerValue) + '"></i>';
        },
        setSourceValue: function(e) {
            e = this.setValue(e);
            if (e !== false && e !== "") {
                if (this.hasInput()) {
                    this.input.val(this.iconpickerValue);
                } else {
                    this.element.data("iconpickerValue", this.iconpickerValue);
                }
                this._trigger("iconpickerSetSourceValue", {
                    iconpickerValue: e
                });
            }
            return e;
        },
        getSourceValue: function(e) {
            e = e || this.options.defaultValue;
            var a = e;
            if (this.hasInput()) {
                a = this.input.val();
            } else {
                a = this.element.data("iconpickerValue");
            }
            if (a === undefined || a === "" || a === null || a === false) {
                a = e;
            }
            return a;
        },
        hasInput: function() {
            return this.input !== false;
        },
        isInputSearch: function() {
            return this.hasInput() && this.options.inputSearch === true;
        },
        isInputGroup: function() {
            return this.container.is(".input-group");
        },
        isDropdownMenu: function() {
            return this.container.is(".dropdown-menu");
        },
        hasSeparatedSearchInput: function() {
            return this.options.templates.search !== false && !this.isInputSearch();
        },
        hasComponent: function() {
            return this.component !== false;
        },
        hasContainer: function() {
            return this.container !== false;
        },
        getAcceptButton: function() {
            return this.popover.find(".iconpicker-btn-accept");
        },
        getCancelButton: function() {
            return this.popover.find(".iconpicker-btn-cancel");
        },
        getSearchInput: function() {
            return this.popover.find(".iconpicker-search");
        },
        filter: function(r) {
            if (l.isEmpty(r)) {
                this.iconpicker.find(".iconpicker-item").show();
                return f(false);
            } else {
                var i = [];
                this.iconpicker.find(".iconpicker-item").each(function() {
                    var e = f(this);
                    var a = e.attr("title").toLowerCase();
                    var t = e.attr("data-search-terms") ? e.attr("data-search-terms").toLowerCase() : "";
                    a = a + " " + t;
                    var s = false;
                    try {
                        s = new RegExp("(^|\\W)" + r, "g");
                    } catch (e) {
                        s = false;
                    }
                    if (s !== false && a.match(s)) {
                        i.push(e);
                        e.show();
                    } else {
                        e.hide();
                    }
                });
                return i;
            }
        },
        show: function() {
            if (this.popover.hasClass("in")) {
                return false;
            }
            f.iconpicker.batch(f(".iconpicker-popover.in:not(.inline)").not(this.popover), "hide");
            this._trigger("iconpickerShow", {
                iconpickerValue: this.iconpickerValue
            });
            this.updatePlacement();
            this.popover.addClass("in");
            setTimeout(f.proxy(function() {
                this.popover.css("display", this.isInline() ? "" : "block");
                this._trigger("iconpickerShown", {
                    iconpickerValue: this.iconpickerValue
                });
            }, this), this.options.animation ? 300 : 1);
        },
        hide: function() {
            if (!this.popover.hasClass("in")) {
                return false;
            }
            this._trigger("iconpickerHide", {
                iconpickerValue: this.iconpickerValue
            });
            this.popover.removeClass("in");
            setTimeout(f.proxy(function() {
                this.popover.css("display", "none");
                this.getSearchInput().val("");
                this.filter("");
                this._trigger("iconpickerHidden", {
                    iconpickerValue: this.iconpickerValue
                });
            }, this), this.options.animation ? 300 : 1);
        },
        toggle: function() {
            if (this.popover.is(":visible")) {
                this.hide();
            } else {
                this.show(true);
            }
        },
        update: function(e, a) {
            e = e ? e : this.getSourceValue(this.iconpickerValue);
            this._trigger("iconpickerUpdate", {
                iconpickerValue: this.iconpickerValue
            });
            if (a === true) {
                e = this.setValue(e);
            } else {
                e = this.setSourceValue(e);
                this._updateFormGroupStatus(e !== false);
            }
            if (e !== false) {
                this._updateComponents();
            }
            this._trigger("iconpickerUpdated", {
                iconpickerValue: this.iconpickerValue
            });
            return e;
        },
        destroy: function() {
            this._trigger("iconpickerDestroy", {
                iconpickerValue: this.iconpickerValue
            });
            this.element.removeData("iconpicker").removeData("iconpickerValue").removeClass("iconpicker-element");
            this._unbindElementEvents();
            this._unbindWindowEvents();
            f(this.popover).remove();
            this._trigger("iconpickerDestroyed", {
                iconpickerValue: this.iconpickerValue
            });
        },
        disable: function() {
            if (this.hasInput()) {
                this.input.prop("disabled", true);
                return true;
            }
            return false;
        },
        enable: function() {
            if (this.hasInput()) {
                this.input.prop("disabled", false);
                return true;
            }
            return false;
        },
        isDisabled: function() {
            if (this.hasInput()) {
                return this.input.prop("disabled") === true;
            }
            return false;
        },
        isInline: function() {
            return this.options.placement === "inline" || this.popover.hasClass("inline");
        }
    };
    f.iconpicker = t;
    f.fn.iconpicker = function(a) {
        return this.each(function() {
            var e = f(this);
            if (!e.data("iconpicker")) {
                e.data("iconpicker", new t(this, typeof a === "object" ? a : {}));
            }
        });
    };
    t.defaultOptions = f.extend(t.defaultOptions, {
        icons: [ {
            title: "fab fa-500px",
            searchTerms: []
        }, {
            title: "fab fa-accessible-icon",
            searchTerms: [ "accessibility", "wheelchair", "handicap", "person", "wheelchair-alt" ]
        }, {
            title: "fab fa-accusoft",
            searchTerms: []
        }, {
            title: "fas fa-address-book",
            searchTerms: []
        }, {
            title: "far fa-address-book",
            searchTerms: []
        }, {
            title: "fas fa-address-card",
            searchTerms: []
        }, {
            title: "far fa-address-card",
            searchTerms: []
        }, {
            title: "fas fa-adjust",
            searchTerms: [ "contrast" ]
        }, {
            title: "fab fa-adn",
            searchTerms: []
        }, {
            title: "fab fa-adversal",
            searchTerms: []
        }, {
            title: "fab fa-affiliatetheme",
            searchTerms: []
        }, {
            title: "fab fa-algolia",
            searchTerms: []
        }, {
            title: "fas fa-align-center",
            searchTerms: [ "middle", "text" ]
        }, {
            title: "fas fa-align-justify",
            searchTerms: [ "text" ]
        }, {
            title: "fas fa-align-left",
            searchTerms: [ "text" ]
        }, {
            title: "fas fa-align-right",
            searchTerms: [ "text" ]
        }, {
            title: "fab fa-amazon",
            searchTerms: []
        }, {
            title: "fab fa-amazon-pay",
            searchTerms: []
        }, {
            title: "fas fa-ambulance",
            searchTerms: [ "vehicle", "support", "help" ]
        }, {
            title: "fas fa-american-sign-language-interpreting",
            searchTerms: []
        }, {
            title: "fab fa-amilia",
            searchTerms: []
        }, {
            title: "fas fa-anchor",
            searchTerms: [ "link" ]
        }, {
            title: "fab fa-android",
            searchTerms: [ "robot" ]
        }, {
            title: "fab fa-angellist",
            searchTerms: []
        }, {
            title: "fas fa-angle-double-down",
            searchTerms: [ "arrows" ]
        }, {
            title: "fas fa-angle-double-left",
            searchTerms: [ "laquo", "quote", "previous", "back", "arrows" ]
        }, {
            title: "fas fa-angle-double-right",
            searchTerms: [ "raquo", "quote", "next", "forward", "arrows" ]
        }, {
            title: "fas fa-angle-double-up",
            searchTerms: [ "arrows" ]
        }, {
            title: "fas fa-angle-down",
            searchTerms: [ "arrow" ]
        }, {
            title: "fas fa-angle-left",
            searchTerms: [ "previous", "back", "arrow" ]
        }, {
            title: "fas fa-angle-right",
            searchTerms: [ "next", "forward", "arrow" ]
        }, {
            title: "fas fa-angle-up",
            searchTerms: [ "arrow" ]
        }, {
            title: "fab fa-angrycreative",
            searchTerms: []
        }, {
            title: "fab fa-angular",
            searchTerms: []
        }, {
            title: "fab fa-app-store",
            searchTerms: []
        }, {
            title: "fab fa-app-store-ios",
            searchTerms: []
        }, {
            title: "fab fa-apper",
            searchTerms: []
        }, {
            title: "fab fa-apple",
            searchTerms: [ "osx", "food" ]
        }, {
            title: "fab fa-apple-pay",
            searchTerms: []
        }, {
            title: "fas fa-archive",
            searchTerms: [ "box", "storage", "package" ]
        }, {
            title: "fas fa-arrow-alt-circle-down",
            searchTerms: [ "download", "arrow-circle-o-down" ]
        }, {
            title: "far fa-arrow-alt-circle-down",
            searchTerms: [ "download", "arrow-circle-o-down" ]
        }, {
            title: "fas fa-arrow-alt-circle-left",
            searchTerms: [ "previous", "back", "arrow-circle-o-left" ]
        }, {
            title: "far fa-arrow-alt-circle-left",
            searchTerms: [ "previous", "back", "arrow-circle-o-left" ]
        }, {
            title: "fas fa-arrow-alt-circle-right",
            searchTerms: [ "next", "forward", "arrow-circle-o-right" ]
        }, {
            title: "far fa-arrow-alt-circle-right",
            searchTerms: [ "next", "forward", "arrow-circle-o-right" ]
        }, {
            title: "fas fa-arrow-alt-circle-up",
            searchTerms: [ "arrow-circle-o-up" ]
        }, {
            title: "far fa-arrow-alt-circle-up",
            searchTerms: [ "arrow-circle-o-up" ]
        }, {
            title: "fas fa-arrow-circle-down",
            searchTerms: [ "download" ]
        }, {
            title: "fas fa-arrow-circle-left",
            searchTerms: [ "previous", "back" ]
        }, {
            title: "fas fa-arrow-circle-right",
            searchTerms: [ "next", "forward" ]
        }, {
            title: "fas fa-arrow-circle-up",
            searchTerms: []
        }, {
            title: "fas fa-arrow-down",
            searchTerms: [ "download" ]
        }, {
            title: "fas fa-arrow-left",
            searchTerms: [ "previous", "back" ]
        }, {
            title: "fas fa-arrow-right",
            searchTerms: [ "next", "forward" ]
        }, {
            title: "fas fa-arrow-up",
            searchTerms: []
        }, {
            title: "fas fa-arrows-alt",
            searchTerms: [ "expand", "enlarge", "fullscreen", "bigger", "move", "reorder", "resize", "arrow", "arrows" ]
        }, {
            title: "fas fa-arrows-alt-h",
            searchTerms: [ "resize", "arrows-h" ]
        }, {
            title: "fas fa-arrows-alt-v",
            searchTerms: [ "resize", "arrows-v" ]
        }, {
            title: "fas fa-assistive-listening-systems",
            searchTerms: []
        }, {
            title: "fas fa-asterisk",
            searchTerms: [ "details" ]
        }, {
            title: "fab fa-asymmetrik",
            searchTerms: []
        }, {
            title: "fas fa-at",
            searchTerms: [ "email", "e-mail" ]
        }, {
            title: "fab fa-audible",
            searchTerms: []
        }, {
            title: "fas fa-audio-description",
            searchTerms: []
        }, {
            title: "fab fa-autoprefixer",
            searchTerms: []
        }, {
            title: "fab fa-avianex",
            searchTerms: []
        }, {
            title: "fab fa-aviato",
            searchTerms: []
        }, {
            title: "fab fa-aws",
            searchTerms: []
        }, {
            title: "fas fa-backward",
            searchTerms: [ "rewind", "previous" ]
        }, {
            title: "fas fa-balance-scale",
            searchTerms: []
        }, {
            title: "fas fa-ban",
            searchTerms: [ "delete", "remove", "trash", "hide", "block", "stop", "abort", "cancel", "ban", "prohibit" ]
        }, {
            title: "fas fa-band-aid",
            searchTerms: [ "bandage", "ouch", "boo boo" ]
        }, {
            title: "fab fa-bandcamp",
            searchTerms: []
        }, {
            title: "fas fa-barcode",
            searchTerms: [ "scan" ]
        }, {
            title: "fas fa-bars",
            searchTerms: [ "menu", "drag", "reorder", "settings", "list", "ul", "ol", "checklist", "todo", "list", "hamburger" ]
        }, {
            title: "fas fa-baseball-ball",
            searchTerms: []
        }, {
            title: "fas fa-basketball-ball",
            searchTerms: []
        }, {
            title: "fas fa-bath",
            searchTerms: []
        }, {
            title: "fas fa-battery-empty",
            searchTerms: [ "power", "status" ]
        }, {
            title: "fas fa-battery-full",
            searchTerms: [ "power", "status" ]
        }, {
            title: "fas fa-battery-half",
            searchTerms: [ "power", "status" ]
        }, {
            title: "fas fa-battery-quarter",
            searchTerms: [ "power", "status" ]
        }, {
            title: "fas fa-battery-three-quarters",
            searchTerms: [ "power", "status" ]
        }, {
            title: "fas fa-bed",
            searchTerms: [ "travel" ]
        }, {
            title: "fas fa-beer",
            searchTerms: [ "alcohol", "stein", "drink", "mug", "bar", "liquor" ]
        }, {
            title: "fab fa-behance",
            searchTerms: []
        }, {
            title: "fab fa-behance-square",
            searchTerms: []
        }, {
            title: "fas fa-bell",
            searchTerms: [ "alert", "reminder", "notification" ]
        }, {
            title: "far fa-bell",
            searchTerms: [ "alert", "reminder", "notification" ]
        }, {
            title: "fas fa-bell-slash",
            searchTerms: []
        }, {
            title: "far fa-bell-slash",
            searchTerms: []
        }, {
            title: "fas fa-bicycle",
            searchTerms: [ "vehicle", "bike", "gears" ]
        }, {
            title: "fab fa-bimobject",
            searchTerms: []
        }, {
            title: "fas fa-binoculars",
            searchTerms: []
        }, {
            title: "fas fa-birthday-cake",
            searchTerms: []
        }, {
            title: "fab fa-bitbucket",
            searchTerms: [ "git", "bitbucket-square" ]
        }, {
            title: "fab fa-bitcoin",
            searchTerms: []
        }, {
            title: "fab fa-bity",
            searchTerms: []
        }, {
            title: "fab fa-black-tie",
            searchTerms: []
        }, {
            title: "fab fa-blackberry",
            searchTerms: []
        }, {
            title: "fas fa-blind",
            searchTerms: []
        }, {
            title: "fab fa-blogger",
            searchTerms: []
        }, {
            title: "fab fa-blogger-b",
            searchTerms: []
        }, {
            title: "fab fa-bluetooth",
            searchTerms: []
        }, {
            title: "fab fa-bluetooth-b",
            searchTerms: []
        }, {
            title: "fas fa-bold",
            searchTerms: []
        }, {
            title: "fas fa-bolt",
            searchTerms: [ "lightning", "weather" ]
        }, {
            title: "fas fa-bomb",
            searchTerms: []
        }, {
            title: "fas fa-book",
            searchTerms: [ "read", "documentation" ]
        }, {
            title: "fas fa-bookmark",
            searchTerms: [ "save" ]
        }, {
            title: "far fa-bookmark",
            searchTerms: [ "save" ]
        }, {
            title: "fas fa-bowling-ball",
            searchTerms: []
        }, {
            title: "fas fa-box",
            searchTerms: []
        }, {
            title: "fas fa-boxes",
            searchTerms: []
        }, {
            title: "fas fa-braille",
            searchTerms: []
        }, {
            title: "fas fa-briefcase",
            searchTerms: [ "work", "business", "office", "luggage", "bag" ]
        }, {
            title: "fab fa-btc",
            searchTerms: []
        }, {
            title: "fas fa-bug",
            searchTerms: [ "report", "insect" ]
        }, {
            title: "fas fa-building",
            searchTerms: [ "work", "business", "apartment", "office", "company" ]
        }, {
            title: "far fa-building",
            searchTerms: [ "work", "business", "apartment", "office", "company" ]
        }, {
            title: "fas fa-bullhorn",
            searchTerms: [ "announcement", "share", "broadcast", "louder", "megaphone" ]
        }, {
            title: "fas fa-bullseye",
            searchTerms: [ "target" ]
        }, {
            title: "fab fa-buromobelexperte",
            searchTerms: []
        }, {
            title: "fas fa-bus",
            searchTerms: [ "vehicle" ]
        }, {
            title: "fab fa-buysellads",
            searchTerms: []
        }, {
            title: "fas fa-calculator",
            searchTerms: []
        }, {
            title: "fas fa-calendar",
            searchTerms: [ "date", "time", "when", "event", "calendar-o" ]
        }, {
            title: "far fa-calendar",
            searchTerms: [ "date", "time", "when", "event", "calendar-o" ]
        }, {
            title: "fas fa-calendar-alt",
            searchTerms: [ "date", "time", "when", "event", "calendar" ]
        }, {
            title: "far fa-calendar-alt",
            searchTerms: [ "date", "time", "when", "event", "calendar" ]
        }, {
            title: "fas fa-calendar-check",
            searchTerms: [ "ok" ]
        }, {
            title: "far fa-calendar-check",
            searchTerms: [ "ok" ]
        }, {
            title: "fas fa-calendar-minus",
            searchTerms: []
        }, {
            title: "far fa-calendar-minus",
            searchTerms: []
        }, {
            title: "fas fa-calendar-plus",
            searchTerms: []
        }, {
            title: "far fa-calendar-plus",
            searchTerms: []
        }, {
            title: "fas fa-calendar-times",
            searchTerms: []
        }, {
            title: "far fa-calendar-times",
            searchTerms: []
        }, {
            title: "fas fa-camera",
            searchTerms: [ "photo", "picture", "record" ]
        }, {
            title: "fas fa-camera-retro",
            searchTerms: [ "photo", "picture", "record" ]
        }, {
            title: "fas fa-car",
            searchTerms: [ "vehicle" ]
        }, {
            title: "fas fa-caret-down",
            searchTerms: [ "more", "dropdown", "menu", "triangle down", "arrow" ]
        }, {
            title: "fas fa-caret-left",
            searchTerms: [ "previous", "back", "triangle left", "arrow" ]
        }, {
            title: "fas fa-caret-right",
            searchTerms: [ "next", "forward", "triangle right", "arrow" ]
        }, {
            title: "fas fa-caret-square-down",
            searchTerms: [ "more", "dropdown", "menu", "caret-square-o-down" ]
        }, {
            title: "far fa-caret-square-down",
            searchTerms: [ "more", "dropdown", "menu", "caret-square-o-down" ]
        }, {
            title: "fas fa-caret-square-left",
            searchTerms: [ "previous", "back", "caret-square-o-left" ]
        }, {
            title: "far fa-caret-square-left",
            searchTerms: [ "previous", "back", "caret-square-o-left" ]
        }, {
            title: "fas fa-caret-square-right",
            searchTerms: [ "next", "forward", "caret-square-o-right" ]
        }, {
            title: "far fa-caret-square-right",
            searchTerms: [ "next", "forward", "caret-square-o-right" ]
        }, {
            title: "fas fa-caret-square-up",
            searchTerms: [ "caret-square-o-up" ]
        }, {
            title: "far fa-caret-square-up",
            searchTerms: [ "caret-square-o-up" ]
        }, {
            title: "fas fa-caret-up",
            searchTerms: [ "triangle up", "arrow" ]
        }, {
            title: "fas fa-cart-arrow-down",
            searchTerms: [ "shopping" ]
        }, {
            title: "fas fa-cart-plus",
            searchTerms: [ "add", "shopping" ]
        }, {
            title: "fab fa-cc-amazon-pay",
            searchTerms: []
        }, {
            title: "fab fa-cc-amex",
            searchTerms: [ "amex" ]
        }, {
            title: "fab fa-cc-apple-pay",
            searchTerms: []
        }, {
            title: "fab fa-cc-diners-club",
            searchTerms: []
        }, {
            title: "fab fa-cc-discover",
            searchTerms: []
        }, {
            title: "fab fa-cc-jcb",
            searchTerms: []
        }, {
            title: "fab fa-cc-mastercard",
            searchTerms: []
        }, {
            title: "fab fa-cc-paypal",
            searchTerms: []
        }, {
            title: "fab fa-cc-stripe",
            searchTerms: []
        }, {
            title: "fab fa-cc-visa",
            searchTerms: []
        }, {
            title: "fab fa-centercode",
            searchTerms: []
        }, {
            title: "fas fa-certificate",
            searchTerms: [ "badge", "star" ]
        }, {
            title: "fas fa-chart-area",
            searchTerms: [ "graph", "analytics", "area-chart" ]
        }, {
            title: "fas fa-chart-bar",
            searchTerms: [ "graph", "analytics", "bar-chart" ]
        }, {
            title: "far fa-chart-bar",
            searchTerms: [ "graph", "analytics", "bar-chart" ]
        }, {
            title: "fas fa-chart-line",
            searchTerms: [ "graph", "analytics", "line-chart", "dashboard" ]
        }, {
            title: "fas fa-chart-pie",
            searchTerms: [ "graph", "analytics", "pie-chart" ]
        }, {
            title: "fas fa-check",
            searchTerms: [ "checkmark", "done", "todo", "agree", "accept", "confirm", "tick", "ok", "select" ]
        }, {
            title: "fas fa-check-circle",
            searchTerms: [ "todo", "done", "agree", "accept", "confirm", "ok", "select" ]
        }, {
            title: "far fa-check-circle",
            searchTerms: [ "todo", "done", "agree", "accept", "confirm", "ok", "select" ]
        }, {
            title: "fas fa-check-square",
            searchTerms: [ "checkmark", "done", "todo", "agree", "accept", "confirm", "ok", "select" ]
        }, {
            title: "far fa-check-square",
            searchTerms: [ "checkmark", "done", "todo", "agree", "accept", "confirm", "ok", "select" ]
        }, {
            title: "fas fa-chess",
            searchTerms: []
        }, {
            title: "fas fa-chess-bishop",
            searchTerms: []
        }, {
            title: "fas fa-chess-board",
            searchTerms: []
        }, {
            title: "fas fa-chess-king",
            searchTerms: []
        }, {
            title: "fas fa-chess-knight",
            searchTerms: []
        }, {
            title: "fas fa-chess-pawn",
            searchTerms: []
        }, {
            title: "fas fa-chess-queen",
            searchTerms: []
        }, {
            title: "fas fa-chess-rook",
            searchTerms: []
        }, {
            title: "fas fa-chevron-circle-down",
            searchTerms: [ "more", "dropdown", "menu", "arrow" ]
        }, {
            title: "fas fa-chevron-circle-left",
            searchTerms: [ "previous", "back", "arrow" ]
        }, {
            title: "fas fa-chevron-circle-right",
            searchTerms: [ "next", "forward", "arrow" ]
        }, {
            title: "fas fa-chevron-circle-up",
            searchTerms: [ "arrow" ]
        }, {
            title: "fas fa-chevron-down",
            searchTerms: []
        }, {
            title: "fas fa-chevron-left",
            searchTerms: [ "bracket", "previous", "back" ]
        }, {
            title: "fas fa-chevron-right",
            searchTerms: [ "bracket", "next", "forward" ]
        }, {
            title: "fas fa-chevron-up",
            searchTerms: []
        }, {
            title: "fas fa-child",
            searchTerms: []
        }, {
            title: "fab fa-chrome",
            searchTerms: [ "browser" ]
        }, {
            title: "fas fa-circle",
            searchTerms: [ "dot", "notification", "circle-thin" ]
        }, {
            title: "far fa-circle",
            searchTerms: [ "dot", "notification", "circle-thin" ]
        }, {
            title: "fas fa-circle-notch",
            searchTerms: [ "circle-o-notch" ]
        }, {
            title: "fas fa-clipboard",
            searchTerms: [ "paste" ]
        }, {
            title: "far fa-clipboard",
            searchTerms: [ "paste" ]
        }, {
            title: "fas fa-clipboard-check",
            searchTerms: []
        }, {
            title: "fas fa-clipboard-list",
            searchTerms: []
        }, {
            title: "fas fa-clock",
            searchTerms: [ "watch", "timer", "late", "timestamp", "date" ]
        }, {
            title: "far fa-clock",
            searchTerms: [ "watch", "timer", "late", "timestamp", "date" ]
        }, {
            title: "fas fa-clone",
            searchTerms: [ "copy" ]
        }, {
            title: "far fa-clone",
            searchTerms: [ "copy" ]
        }, {
            title: "fas fa-closed-captioning",
            searchTerms: [ "cc" ]
        }, {
            title: "far fa-closed-captioning",
            searchTerms: [ "cc" ]
        }, {
            title: "fas fa-cloud",
            searchTerms: [ "save" ]
        }, {
            title: "fas fa-cloud-download-alt",
            searchTerms: [ "cloud-download" ]
        }, {
            title: "fas fa-cloud-upload-alt",
            searchTerms: [ "cloud-upload" ]
        }, {
            title: "fab fa-cloudscale",
            searchTerms: []
        }, {
            title: "fab fa-cloudsmith",
            searchTerms: []
        }, {
            title: "fab fa-cloudversify",
            searchTerms: []
        }, {
            title: "fas fa-code",
            searchTerms: [ "html", "brackets" ]
        }, {
            title: "fas fa-code-branch",
            searchTerms: [ "git", "fork", "vcs", "svn", "github", "rebase", "version", "branch", "code-fork" ]
        }, {
            title: "fab fa-codepen",
            searchTerms: []
        }, {
            title: "fab fa-codiepie",
            searchTerms: []
        }, {
            title: "fas fa-coffee",
            searchTerms: [ "morning", "mug", "breakfast", "tea", "drink", "cafe" ]
        }, {
            title: "fas fa-cog",
            searchTerms: [ "settings" ]
        }, {
            title: "fas fa-cogs",
            searchTerms: [ "settings", "gears" ]
        }, {
            title: "fas fa-columns",
            searchTerms: [ "split", "panes", "dashboard" ]
        }, {
            title: "fas fa-comment",
            searchTerms: [ "speech", "notification", "note", "chat", "bubble", "feedback", "message", "texting", "sms", "conversation" ]
        }, {
            title: "far fa-comment",
            searchTerms: [ "speech", "notification", "note", "chat", "bubble", "feedback", "message", "texting", "sms", "conversation" ]
        }, {
            title: "fas fa-comment-alt",
            searchTerms: [ "speech", "notification", "note", "chat", "bubble", "feedback", "message", "texting", "sms", "conversation", "commenting", "commenting" ]
        }, {
            title: "far fa-comment-alt",
            searchTerms: [ "speech", "notification", "note", "chat", "bubble", "feedback", "message", "texting", "sms", "conversation", "commenting", "commenting" ]
        }, {
            title: "fas fa-comments",
            searchTerms: [ "speech", "notification", "note", "chat", "bubble", "feedback", "message", "texting", "sms", "conversation" ]
        }, {
            title: "far fa-comments",
            searchTerms: [ "speech", "notification", "note", "chat", "bubble", "feedback", "message", "texting", "sms", "conversation" ]
        }, {
            title: "fas fa-compass",
            searchTerms: [ "safari", "directory", "menu", "location" ]
        }, {
            title: "far fa-compass",
            searchTerms: [ "safari", "directory", "menu", "location" ]
        }, {
            title: "fas fa-compress",
            searchTerms: [ "collapse", "combine", "contract", "merge", "smaller" ]
        }, {
            title: "fab fa-connectdevelop",
            searchTerms: []
        }, {
            title: "fab fa-contao",
            searchTerms: []
        }, {
            title: "fas fa-copy",
            searchTerms: [ "duplicate", "clone", "file", "files-o" ]
        }, {
            title: "far fa-copy",
            searchTerms: [ "duplicate", "clone", "file", "files-o" ]
        }, {
            title: "fas fa-copyright",
            searchTerms: []
        }, {
            title: "far fa-copyright",
            searchTerms: []
        }, {
            title: "fab fa-cpanel",
            searchTerms: []
        }, {
            title: "fab fa-creative-commons",
            searchTerms: []
        }, {
            title: "fas fa-credit-card",
            searchTerms: [ "money", "buy", "debit", "checkout", "purchase", "payment", "credit-card-alt" ]
        }, {
            title: "far fa-credit-card",
            searchTerms: [ "money", "buy", "debit", "checkout", "purchase", "payment", "credit-card-alt" ]
        }, {
            title: "fas fa-crop",
            searchTerms: [ "design" ]
        }, {
            title: "fas fa-crosshairs",
            searchTerms: [ "picker", "gpd" ]
        }, {
            title: "fab fa-css3",
            searchTerms: [ "code" ]
        }, {
            title: "fab fa-css3-alt",
            searchTerms: []
        }, {
            title: "fas fa-cube",
            searchTerms: [ "package" ]
        }, {
            title: "fas fa-cubes",
            searchTerms: [ "packages" ]
        }, {
            title: "fas fa-cut",
            searchTerms: [ "scissors", "scissors" ]
        }, {
            title: "fab fa-cuttlefish",
            searchTerms: []
        }, {
            title: "fab fa-d-and-d",
            searchTerms: []
        }, {
            title: "fab fa-dashcube",
            searchTerms: []
        }, {
            title: "fas fa-database",
            searchTerms: []
        }, {
            title: "fas fa-deaf",
            searchTerms: []
        }, {
            title: "fab fa-delicious",
            searchTerms: []
        }, {
            title: "fab fa-deploydog",
            searchTerms: []
        }, {
            title: "fab fa-deskpro",
            searchTerms: []
        }, {
            title: "fas fa-desktop",
            searchTerms: [ "monitor", "screen", "desktop", "computer", "demo", "device", "pc" ]
        }, {
            title: "fab fa-deviantart",
            searchTerms: []
        }, {
            title: "fab fa-digg",
            searchTerms: []
        }, {
            title: "fab fa-digital-ocean",
            searchTerms: []
        }, {
            title: "fab fa-discord",
            searchTerms: []
        }, {
            title: "fab fa-discourse",
            searchTerms: []
        }, {
            title: "fas fa-dna",
            searchTerms: [ "double helix", "helix" ]
        }, {
            title: "fab fa-dochub",
            searchTerms: []
        }, {
            title: "fab fa-docker",
            searchTerms: []
        }, {
            title: "fas fa-dollar-sign",
            searchTerms: [ "usd", "price" ]
        }, {
            title: "fas fa-dolly",
            searchTerms: []
        }, {
            title: "fas fa-dolly-flatbed",
            searchTerms: []
        }, {
            title: "fas fa-dot-circle",
            searchTerms: [ "target", "bullseye", "notification" ]
        }, {
            title: "far fa-dot-circle",
            searchTerms: [ "target", "bullseye", "notification" ]
        }, {
            title: "fas fa-download",
            searchTerms: [ "import" ]
        }, {
            title: "fab fa-draft2digital",
            searchTerms: []
        }, {
            title: "fab fa-dribbble",
            searchTerms: []
        }, {
            title: "fab fa-dribbble-square",
            searchTerms: []
        }, {
            title: "fab fa-dropbox",
            searchTerms: []
        }, {
            title: "fab fa-drupal",
            searchTerms: []
        }, {
            title: "fab fa-dyalog",
            searchTerms: []
        }, {
            title: "fab fa-earlybirds",
            searchTerms: []
        }, {
            title: "fab fa-edge",
            searchTerms: [ "browser", "ie" ]
        }, {
            title: "fas fa-edit",
            searchTerms: [ "write", "edit", "update", "pencil", "pen" ]
        }, {
            title: "far fa-edit",
            searchTerms: [ "write", "edit", "update", "pencil", "pen" ]
        }, {
            title: "fas fa-eject",
            searchTerms: []
        }, {
            title: "fab fa-elementor",
            searchTerms: []
        }, {
            title: "fas fa-ellipsis-h",
            searchTerms: [ "dots" ]
        }, {
            title: "fas fa-ellipsis-v",
            searchTerms: [ "dots" ]
        }, {
            title: "fab fa-ember",
            searchTerms: []
        }, {
            title: "fab fa-empire",
            searchTerms: []
        }, {
            title: "fas fa-envelope",
            searchTerms: [ "email", "e-mail", "letter", "support", "mail", "message", "notification" ]
        }, {
            title: "far fa-envelope",
            searchTerms: [ "email", "e-mail", "letter", "support", "mail", "message", "notification" ]
        }, {
            title: "fas fa-envelope-open",
            searchTerms: [ "email", "e-mail", "letter", "support", "mail", "message", "notification" ]
        }, {
            title: "far fa-envelope-open",
            searchTerms: [ "email", "e-mail", "letter", "support", "mail", "message", "notification" ]
        }, {
            title: "fas fa-envelope-square",
            searchTerms: [ "email", "e-mail", "letter", "support", "mail", "message", "notification" ]
        }, {
            title: "fab fa-envira",
            searchTerms: [ "leaf" ]
        }, {
            title: "fas fa-eraser",
            searchTerms: [ "remove", "delete" ]
        }, {
            title: "fab fa-erlang",
            searchTerms: []
        }, {
            title: "fab fa-ethereum",
            searchTerms: []
        }, {
            title: "fab fa-etsy",
            searchTerms: []
        }, {
            title: "fas fa-euro-sign",
            searchTerms: [ "eur", "eur" ]
        }, {
            title: "fas fa-exchange-alt",
            searchTerms: [ "transfer", "arrows", "arrow", "exchange", "swap" ]
        }, {
            title: "fas fa-exclamation",
            searchTerms: [ "warning", "error", "problem", "notification", "notify", "alert", "danger" ]
        }, {
            title: "fas fa-exclamation-circle",
            searchTerms: [ "warning", "error", "problem", "notification", "notify", "alert", "danger" ]
        }, {
            title: "fas fa-exclamation-triangle",
            searchTerms: [ "warning", "error", "problem", "notification", "notify", "alert", "danger" ]
        }, {
            title: "fas fa-expand",
            searchTerms: [ "enlarge", "bigger", "resize" ]
        }, {
            title: "fas fa-expand-arrows-alt",
            searchTerms: [ "enlarge", "bigger", "resize", "move", "arrows-alt" ]
        }, {
            title: "fab fa-expeditedssl",
            searchTerms: []
        }, {
            title: "fas fa-external-link-alt",
            searchTerms: [ "open", "new", "external-link" ]
        }, {
            title: "fas fa-external-link-square-alt",
            searchTerms: [ "open", "new", "external-link-square" ]
        }, {
            title: "fas fa-eye",
            searchTerms: [ "show", "visible", "views" ]
        }, {
            title: "fas fa-eye-dropper",
            searchTerms: [ "eyedropper" ]
        }, {
            title: "fas fa-eye-slash",
            searchTerms: [ "toggle", "show", "hide", "visible", "visiblity", "views" ]
        }, {
            title: "far fa-eye-slash",
            searchTerms: [ "toggle", "show", "hide", "visible", "visiblity", "views" ]
        }, {
            title: "fab fa-facebook",
            searchTerms: [ "social network", "facebook-official" ]
        }, {
            title: "fab fa-facebook-f",
            searchTerms: [ "facebook" ]
        }, {
            title: "fab fa-facebook-messenger",
            searchTerms: []
        }, {
            title: "fab fa-facebook-square",
            searchTerms: [ "social network" ]
        }, {
            title: "fas fa-fast-backward",
            searchTerms: [ "rewind", "previous", "beginning", "start", "first" ]
        }, {
            title: "fas fa-fast-forward",
            searchTerms: [ "next", "end", "last" ]
        }, {
            title: "fas fa-fax",
            searchTerms: []
        }, {
            title: "fas fa-female",
            searchTerms: [ "woman", "human", "user", "person", "profile" ]
        }, {
            title: "fas fa-fighter-jet",
            searchTerms: [ "fly", "plane", "airplane", "quick", "fast", "travel" ]
        }, {
            title: "fas fa-file",
            searchTerms: [ "new", "page", "pdf", "document" ]
        }, {
            title: "far fa-file",
            searchTerms: [ "new", "page", "pdf", "document" ]
        }, {
            title: "fas fa-file-alt",
            searchTerms: [ "new", "page", "pdf", "document", "file-text" ]
        }, {
            title: "far fa-file-alt",
            searchTerms: [ "new", "page", "pdf", "document", "file-text" ]
        }, {
            title: "fas fa-file-archive",
            searchTerms: []
        }, {
            title: "far fa-file-archive",
            searchTerms: []
        }, {
            title: "fas fa-file-audio",
            searchTerms: []
        }, {
            title: "far fa-file-audio",
            searchTerms: []
        }, {
            title: "fas fa-file-code",
            searchTerms: []
        }, {
            title: "far fa-file-code",
            searchTerms: []
        }, {
            title: "fas fa-file-excel",
            searchTerms: []
        }, {
            title: "far fa-file-excel",
            searchTerms: []
        }, {
            title: "fas fa-file-image",
            searchTerms: []
        }, {
            title: "far fa-file-image",
            searchTerms: []
        }, {
            title: "fas fa-file-pdf",
            searchTerms: []
        }, {
            title: "far fa-file-pdf",
            searchTerms: []
        }, {
            title: "fas fa-file-powerpoint",
            searchTerms: []
        }, {
            title: "far fa-file-powerpoint",
            searchTerms: []
        }, {
            title: "fas fa-file-video",
            searchTerms: []
        }, {
            title: "far fa-file-video",
            searchTerms: []
        }, {
            title: "fas fa-file-word",
            searchTerms: []
        }, {
            title: "far fa-file-word",
            searchTerms: []
        }, {
            title: "fas fa-film",
            searchTerms: [ "movie" ]
        }, {
            title: "fas fa-filter",
            searchTerms: [ "funnel", "options" ]
        }, {
            title: "fas fa-fire",
            searchTerms: [ "flame", "hot", "popular" ]
        }, {
            title: "fas fa-fire-extinguisher",
            searchTerms: []
        }, {
            title: "fab fa-firefox",
            searchTerms: [ "browser" ]
        }, {
            title: "fas fa-first-aid",
            searchTerms: []
        }, {
            title: "fab fa-first-order",
            searchTerms: []
        }, {
            title: "fab fa-firstdraft",
            searchTerms: []
        }, {
            title: "fas fa-flag",
            searchTerms: [ "report", "notification", "notify" ]
        }, {
            title: "far fa-flag",
            searchTerms: [ "report", "notification", "notify" ]
        }, {
            title: "fas fa-flag-checkered",
            searchTerms: [ "report", "notification", "notify" ]
        }, {
            title: "fas fa-flask",
            searchTerms: [ "science", "beaker", "experimental", "labs" ]
        }, {
            title: "fab fa-flickr",
            searchTerms: []
        }, {
            title: "fab fa-flipboard",
            searchTerms: []
        }, {
            title: "fab fa-fly",
            searchTerms: []
        }, {
            title: "fas fa-folder",
            searchTerms: []
        }, {
            title: "far fa-folder",
            searchTerms: []
        }, {
            title: "fas fa-folder-open",
            searchTerms: []
        }, {
            title: "far fa-folder-open",
            searchTerms: []
        }, {
            title: "fas fa-font",
            searchTerms: [ "text" ]
        }, {
            title: "fab fa-font-awesome",
            searchTerms: [ "meanpath" ]
        }, {
            title: "fab fa-font-awesome-alt",
            searchTerms: []
        }, {
            title: "fab fa-font-awesome-flag",
            searchTerms: []
        }, {
            title: "fab fa-fonticons",
            searchTerms: []
        }, {
            title: "fab fa-fonticons-fi",
            searchTerms: []
        }, {
            title: "fas fa-football-ball",
            searchTerms: []
        }, {
            title: "fab fa-fort-awesome",
            searchTerms: [ "castle" ]
        }, {
            title: "fab fa-fort-awesome-alt",
            searchTerms: [ "castle" ]
        }, {
            title: "fab fa-forumbee",
            searchTerms: []
        }, {
            title: "fas fa-forward",
            searchTerms: [ "forward", "next" ]
        }, {
            title: "fab fa-foursquare",
            searchTerms: []
        }, {
            title: "fab fa-free-code-camp",
            searchTerms: []
        }, {
            title: "fab fa-freebsd",
            searchTerms: []
        }, {
            title: "fas fa-frown",
            searchTerms: [ "face", "emoticon", "sad", "disapprove", "rating" ]
        }, {
            title: "far fa-frown",
            searchTerms: [ "face", "emoticon", "sad", "disapprove", "rating" ]
        }, {
            title: "fas fa-futbol",
            searchTerms: []
        }, {
            title: "far fa-futbol",
            searchTerms: []
        }, {
            title: "fas fa-gamepad",
            searchTerms: [ "controller" ]
        }, {
            title: "fas fa-gavel",
            searchTerms: [ "judge", "lawyer", "opinion", "hammer" ]
        }, {
            title: "fas fa-gem",
            searchTerms: [ "diamond" ]
        }, {
            title: "far fa-gem",
            searchTerms: [ "diamond" ]
        }, {
            title: "fas fa-genderless",
            searchTerms: []
        }, {
            title: "fab fa-get-pocket",
            searchTerms: []
        }, {
            title: "fab fa-gg",
            searchTerms: []
        }, {
            title: "fab fa-gg-circle",
            searchTerms: []
        }, {
            title: "fas fa-gift",
            searchTerms: [ "present" ]
        }, {
            title: "fab fa-git",
            searchTerms: []
        }, {
            title: "fab fa-git-square",
            searchTerms: []
        }, {
            title: "fab fa-github",
            searchTerms: [ "octocat" ]
        }, {
            title: "fab fa-github-alt",
            searchTerms: [ "octocat" ]
        }, {
            title: "fab fa-github-square",
            searchTerms: [ "octocat" ]
        }, {
            title: "fab fa-gitkraken",
            searchTerms: []
        }, {
            title: "fab fa-gitlab",
            searchTerms: [ "Axosoft" ]
        }, {
            title: "fab fa-gitter",
            searchTerms: []
        }, {
            title: "fas fa-glass-martini",
            searchTerms: [ "martini", "drink", "bar", "alcohol", "liquor", "glass" ]
        }, {
            title: "fab fa-glide",
            searchTerms: []
        }, {
            title: "fab fa-glide-g",
            searchTerms: []
        }, {
            title: "fas fa-globe",
            searchTerms: [ "world", "planet", "map", "place", "travel", "earth", "global", "translate", "all", "language", "localize", "location", "coordinates", "country", "gps" ]
        }, {
            title: "fab fa-gofore",
            searchTerms: []
        }, {
            title: "fas fa-golf-ball",
            searchTerms: []
        }, {
            title: "fab fa-goodreads",
            searchTerms: []
        }, {
            title: "fab fa-goodreads-g",
            searchTerms: []
        }, {
            title: "fab fa-google",
            searchTerms: []
        }, {
            title: "fab fa-google-drive",
            searchTerms: []
        }, {
            title: "fab fa-google-play",
            searchTerms: []
        }, {
            title: "fab fa-google-plus",
            searchTerms: [ "google-plus-circle", "google-plus-official" ]
        }, {
            title: "fab fa-google-plus-g",
            searchTerms: [ "social network", "google-plus" ]
        }, {
            title: "fab fa-google-plus-square",
            searchTerms: [ "social network" ]
        }, {
            title: "fab fa-google-wallet",
            searchTerms: []
        }, {
            title: "fas fa-graduation-cap",
            searchTerms: [ "learning", "school", "student" ]
        }, {
            title: "fab fa-gratipay",
            searchTerms: [ "heart", "like", "favorite", "love" ]
        }, {
            title: "fab fa-grav",
            searchTerms: []
        }, {
            title: "fab fa-gripfire",
            searchTerms: []
        }, {
            title: "fab fa-grunt",
            searchTerms: []
        }, {
            title: "fab fa-gulp",
            searchTerms: []
        }, {
            title: "fas fa-h-square",
            searchTerms: [ "hospital", "hotel" ]
        }, {
            title: "fab fa-hacker-news",
            searchTerms: []
        }, {
            title: "fab fa-hacker-news-square",
            searchTerms: []
        }, {
            title: "fas fa-hand-lizard",
            searchTerms: []
        }, {
            title: "far fa-hand-lizard",
            searchTerms: []
        }, {
            title: "fas fa-hand-paper",
            searchTerms: [ "stop" ]
        }, {
            title: "far fa-hand-paper",
            searchTerms: [ "stop" ]
        }, {
            title: "fas fa-hand-peace",
            searchTerms: []
        }, {
            title: "far fa-hand-peace",
            searchTerms: []
        }, {
            title: "fas fa-hand-point-down",
            searchTerms: [ "point", "finger", "hand-o-down" ]
        }, {
            title: "far fa-hand-point-down",
            searchTerms: [ "point", "finger", "hand-o-down" ]
        }, {
            title: "fas fa-hand-point-left",
            searchTerms: [ "point", "left", "previous", "back", "finger", "hand-o-left" ]
        }, {
            title: "far fa-hand-point-left",
            searchTerms: [ "point", "left", "previous", "back", "finger", "hand-o-left" ]
        }, {
            title: "fas fa-hand-point-right",
            searchTerms: [ "point", "right", "next", "forward", "finger", "hand-o-right" ]
        }, {
            title: "far fa-hand-point-right",
            searchTerms: [ "point", "right", "next", "forward", "finger", "hand-o-right" ]
        }, {
            title: "fas fa-hand-point-up",
            searchTerms: [ "point", "finger", "hand-o-up" ]
        }, {
            title: "far fa-hand-point-up",
            searchTerms: [ "point", "finger", "hand-o-up" ]
        }, {
            title: "fas fa-hand-pointer",
            searchTerms: [ "select" ]
        }, {
            title: "far fa-hand-pointer",
            searchTerms: [ "select" ]
        }, {
            title: "fas fa-hand-rock",
            searchTerms: []
        }, {
            title: "far fa-hand-rock",
            searchTerms: []
        }, {
            title: "fas fa-hand-scissors",
            searchTerms: []
        }, {
            title: "far fa-hand-scissors",
            searchTerms: []
        }, {
            title: "fas fa-hand-spock",
            searchTerms: []
        }, {
            title: "far fa-hand-spock",
            searchTerms: []
        }, {
            title: "fas fa-handshake",
            searchTerms: []
        }, {
            title: "far fa-handshake",
            searchTerms: []
        }, {
            title: "fas fa-hashtag",
            searchTerms: []
        }, {
            title: "fas fa-hdd",
            searchTerms: [ "harddrive", "hard drive", "storage", "save" ]
        }, {
            title: "far fa-hdd",
            searchTerms: [ "harddrive", "hard drive", "storage", "save" ]
        }, {
            title: "fas fa-heading",
            searchTerms: [ "header", "header" ]
        }, {
            title: "fas fa-headphones",
            searchTerms: [ "sound", "listen", "music", "audio" ]
        }, {
            title: "fas fa-heart",
            searchTerms: [ "love", "like", "favorite" ]
        }, {
            title: "far fa-heart",
            searchTerms: [ "love", "like", "favorite" ]
        }, {
            title: "fas fa-heartbeat",
            searchTerms: [ "ekg", "vital signs" ]
        }, {
            title: "fab fa-hips",
            searchTerms: []
        }, {
            title: "fab fa-hire-a-helper",
            searchTerms: []
        }, {
            title: "fas fa-history",
            searchTerms: []
        }, {
            title: "fas fa-hockey-puck",
            searchTerms: []
        }, {
            title: "fas fa-home",
            searchTerms: [ "main", "house" ]
        }, {
            title: "fab fa-hooli",
            searchTerms: []
        }, {
            title: "fas fa-hospital",
            searchTerms: [ "building", "medical center", "emergency room" ]
        }, {
            title: "far fa-hospital",
            searchTerms: [ "building", "medical center", "emergency room" ]
        }, {
            title: "fas fa-hospital-symbol",
            searchTerms: []
        }, {
            title: "fab fa-hotjar",
            searchTerms: []
        }, {
            title: "fas fa-hourglass",
            searchTerms: []
        }, {
            title: "far fa-hourglass",
            searchTerms: []
        }, {
            title: "fas fa-hourglass-end",
            searchTerms: []
        }, {
            title: "fas fa-hourglass-half",
            searchTerms: []
        }, {
            title: "fas fa-hourglass-start",
            searchTerms: []
        }, {
            title: "fab fa-houzz",
            searchTerms: []
        }, {
            title: "fab fa-html5",
            searchTerms: []
        }, {
            title: "fab fa-hubspot",
            searchTerms: []
        }, {
            title: "fas fa-i-cursor",
            searchTerms: []
        }, {
            title: "fas fa-id-badge",
            searchTerms: []
        }, {
            title: "far fa-id-badge",
            searchTerms: []
        }, {
            title: "fas fa-id-card",
            searchTerms: []
        }, {
            title: "far fa-id-card",
            searchTerms: []
        }, {
            title: "fas fa-image",
            searchTerms: [ "photo", "album", "picture", "picture" ]
        }, {
            title: "far fa-image",
            searchTerms: [ "photo", "album", "picture", "picture" ]
        }, {
            title: "fas fa-images",
            searchTerms: [ "photo", "album", "picture" ]
        }, {
            title: "far fa-images",
            searchTerms: [ "photo", "album", "picture" ]
        }, {
            title: "fab fa-imdb",
            searchTerms: []
        }, {
            title: "fas fa-inbox",
            searchTerms: []
        }, {
            title: "fas fa-indent",
            searchTerms: []
        }, {
            title: "fas fa-industry",
            searchTerms: [ "factory" ]
        }, {
            title: "fas fa-info",
            searchTerms: [ "help", "information", "more", "details" ]
        }, {
            title: "fas fa-info-circle",
            searchTerms: [ "help", "information", "more", "details" ]
        }, {
            title: "fab fa-instagram",
            searchTerms: []
        }, {
            title: "fab fa-internet-explorer",
            searchTerms: [ "browser", "ie" ]
        }, {
            title: "fab fa-ioxhost",
            searchTerms: []
        }, {
            title: "fas fa-italic",
            searchTerms: [ "italics" ]
        }, {
            title: "fab fa-itunes",
            searchTerms: []
        }, {
            title: "fab fa-itunes-note",
            searchTerms: []
        }, {
            title: "fab fa-jenkins",
            searchTerms: []
        }, {
            title: "fab fa-joget",
            searchTerms: []
        }, {
            title: "fab fa-joomla",
            searchTerms: []
        }, {
            title: "fab fa-js",
            searchTerms: []
        }, {
            title: "fab fa-js-square",
            searchTerms: []
        }, {
            title: "fab fa-jsfiddle",
            searchTerms: []
        }, {
            title: "fas fa-key",
            searchTerms: [ "unlock", "password" ]
        }, {
            title: "fas fa-keyboard",
            searchTerms: [ "type", "input" ]
        }, {
            title: "far fa-keyboard",
            searchTerms: [ "type", "input" ]
        }, {
            title: "fab fa-keycdn",
            searchTerms: []
        }, {
            title: "fab fa-kickstarter",
            searchTerms: []
        }, {
            title: "fab fa-kickstarter-k",
            searchTerms: []
        }, {
            title: "fab fa-korvue",
            searchTerms: []
        }, {
            title: "fas fa-language",
            searchTerms: []
        }, {
            title: "fas fa-laptop",
            searchTerms: [ "demo", "computer", "device", "pc" ]
        }, {
            title: "fab fa-laravel",
            searchTerms: []
        }, {
            title: "fab fa-lastfm",
            searchTerms: []
        }, {
            title: "fab fa-lastfm-square",
            searchTerms: []
        }, {
            title: "fas fa-leaf",
            searchTerms: [ "eco", "nature", "plant" ]
        }, {
            title: "fab fa-leanpub",
            searchTerms: []
        }, {
            title: "fas fa-lemon",
            searchTerms: [ "food" ]
        }, {
            title: "far fa-lemon",
            searchTerms: [ "food" ]
        }, {
            title: "fab fa-less",
            searchTerms: []
        }, {
            title: "fas fa-level-down-alt",
            searchTerms: [ "level-down" ]
        }, {
            title: "fas fa-level-up-alt",
            searchTerms: [ "level-up" ]
        }, {
            title: "fas fa-life-ring",
            searchTerms: [ "support" ]
        }, {
            title: "far fa-life-ring",
            searchTerms: [ "support" ]
        }, {
            title: "fas fa-lightbulb",
            searchTerms: [ "idea", "inspiration" ]
        }, {
            title: "far fa-lightbulb",
            searchTerms: [ "idea", "inspiration" ]
        }, {
            title: "fab fa-line",
            searchTerms: []
        }, {
            title: "fas fa-link",
            searchTerms: [ "chain" ]
        }, {
            title: "fab fa-linkedin",
            searchTerms: [ "linkedin-square" ]
        }, {
            title: "fab fa-linkedin-in",
            searchTerms: [ "linkedin" ]
        }, {
            title: "fab fa-linode",
            searchTerms: []
        }, {
            title: "fab fa-linux",
            searchTerms: [ "tux" ]
        }, {
            title: "fas fa-lira-sign",
            searchTerms: [ "try", "turkish", "try" ]
        }, {
            title: "fas fa-list",
            searchTerms: [ "ul", "ol", "checklist", "finished", "completed", "done", "todo" ]
        }, {
            title: "fas fa-list-alt",
            searchTerms: [ "ul", "ol", "checklist", "finished", "completed", "done", "todo" ]
        }, {
            title: "far fa-list-alt",
            searchTerms: [ "ul", "ol", "checklist", "finished", "completed", "done", "todo" ]
        }, {
            title: "fas fa-list-ol",
            searchTerms: [ "ul", "ol", "checklist", "list", "todo", "list", "numbers" ]
        }, {
            title: "fas fa-list-ul",
            searchTerms: [ "ul", "ol", "checklist", "todo", "list" ]
        }, {
            title: "fas fa-location-arrow",
            searchTerms: [ "map", "coordinates", "location", "address", "place", "where", "gps" ]
        }, {
            title: "fas fa-lock",
            searchTerms: [ "protect", "admin", "security" ]
        }, {
            title: "fas fa-lock-open",
            searchTerms: [ "protect", "admin", "password", "lock", "open" ]
        }, {
            title: "fas fa-long-arrow-alt-down",
            searchTerms: [ "long-arrow-down" ]
        }, {
            title: "fas fa-long-arrow-alt-left",
            searchTerms: [ "previous", "back", "long-arrow-left" ]
        }, {
            title: "fas fa-long-arrow-alt-right",
            searchTerms: [ "long-arrow-right" ]
        }, {
            title: "fas fa-long-arrow-alt-up",
            searchTerms: [ "long-arrow-up" ]
        }, {
            title: "fas fa-low-vision",
            searchTerms: []
        }, {
            title: "fab fa-lyft",
            searchTerms: []
        }, {
            title: "fab fa-magento",
            searchTerms: []
        }, {
            title: "fas fa-magic",
            searchTerms: [ "wizard", "automatic", "autocomplete" ]
        }, {
            title: "fas fa-magnet",
            searchTerms: []
        }, {
            title: "fas fa-male",
            searchTerms: [ "man", "human", "user", "person", "profile" ]
        }, {
            title: "fas fa-map",
            searchTerms: []
        }, {
            title: "far fa-map",
            searchTerms: []
        }, {
            title: "fas fa-map-marker",
            searchTerms: [ "map", "pin", "location", "coordinates", "localize", "address", "travel", "where", "place", "gps" ]
        }, {
            title: "fas fa-map-marker-alt",
            searchTerms: [ "map-marker", "gps" ]
        }, {
            title: "fas fa-map-pin",
            searchTerms: []
        }, {
            title: "fas fa-map-signs",
            searchTerms: []
        }, {
            title: "fas fa-mars",
            searchTerms: [ "male" ]
        }, {
            title: "fas fa-mars-double",
            searchTerms: []
        }, {
            title: "fas fa-mars-stroke",
            searchTerms: []
        }, {
            title: "fas fa-mars-stroke-h",
            searchTerms: []
        }, {
            title: "fas fa-mars-stroke-v",
            searchTerms: []
        }, {
            title: "fab fa-maxcdn",
            searchTerms: []
        }, {
            title: "fab fa-medapps",
            searchTerms: []
        }, {
            title: "fab fa-medium",
            searchTerms: []
        }, {
            title: "fab fa-medium-m",
            searchTerms: []
        }, {
            title: "fas fa-medkit",
            searchTerms: [ "first aid", "firstaid", "help", "support", "health" ]
        }, {
            title: "fab fa-medrt",
            searchTerms: []
        }, {
            title: "fab fa-meetup",
            searchTerms: []
        }, {
            title: "fas fa-meh",
            searchTerms: [ "face", "emoticon", "rating", "neutral" ]
        }, {
            title: "far fa-meh",
            searchTerms: [ "face", "emoticon", "rating", "neutral" ]
        }, {
            title: "fas fa-mercury",
            searchTerms: [ "transgender" ]
        }, {
            title: "fas fa-microchip",
            searchTerms: []
        }, {
            title: "fas fa-microphone",
            searchTerms: [ "record", "voice", "sound" ]
        }, {
            title: "fas fa-microphone-slash",
            searchTerms: [ "record", "voice", "sound", "mute" ]
        }, {
            title: "fab fa-microsoft",
            searchTerms: []
        }, {
            title: "fas fa-minus",
            searchTerms: [ "hide", "minify", "delete", "remove", "trash", "hide", "collapse" ]
        }, {
            title: "fas fa-minus-circle",
            searchTerms: [ "delete", "remove", "trash", "hide" ]
        }, {
            title: "fas fa-minus-square",
            searchTerms: [ "hide", "minify", "delete", "remove", "trash", "hide", "collapse" ]
        }, {
            title: "far fa-minus-square",
            searchTerms: [ "hide", "minify", "delete", "remove", "trash", "hide", "collapse" ]
        }, {
            title: "fab fa-mix",
            searchTerms: []
        }, {
            title: "fab fa-mixcloud",
            searchTerms: []
        }, {
            title: "fab fa-mizuni",
            searchTerms: []
        }, {
            title: "fas fa-mobile",
            searchTerms: [ "cell phone", "cellphone", "text", "call", "iphone", "number", "telephone" ]
        }, {
            title: "fas fa-mobile-alt",
            searchTerms: [ "mobile" ]
        }, {
            title: "fab fa-modx",
            searchTerms: []
        }, {
            title: "fab fa-monero",
            searchTerms: []
        }, {
            title: "fas fa-money-bill-alt",
            searchTerms: [ "cash", "money", "buy", "checkout", "purchase", "payment", "price" ]
        }, {
            title: "far fa-money-bill-alt",
            searchTerms: [ "cash", "money", "buy", "checkout", "purchase", "payment", "price" ]
        }, {
            title: "fas fa-moon",
            searchTerms: [ "night", "darker", "contrast" ]
        }, {
            title: "far fa-moon",
            searchTerms: [ "night", "darker", "contrast" ]
        }, {
            title: "fas fa-motorcycle",
            searchTerms: [ "vehicle", "bike" ]
        }, {
            title: "fas fa-mouse-pointer",
            searchTerms: [ "select" ]
        }, {
            title: "fas fa-music",
            searchTerms: [ "note", "sound" ]
        }, {
            title: "fab fa-napster",
            searchTerms: []
        }, {
            title: "fas fa-neuter",
            searchTerms: []
        }, {
            title: "fas fa-newspaper",
            searchTerms: [ "press", "article" ]
        }, {
            title: "far fa-newspaper",
            searchTerms: [ "press", "article" ]
        }, {
            title: "fab fa-nintendo-switch",
            searchTerms: []
        }, {
            title: "fab fa-node",
            searchTerms: []
        }, {
            title: "fab fa-node-js",
            searchTerms: []
        }, {
            title: "fab fa-npm",
            searchTerms: []
        }, {
            title: "fab fa-ns8",
            searchTerms: []
        }, {
            title: "fab fa-nutritionix",
            searchTerms: []
        }, {
            title: "fas fa-object-group",
            searchTerms: [ "design" ]
        }, {
            title: "far fa-object-group",
            searchTerms: [ "design" ]
        }, {
            title: "fas fa-object-ungroup",
            searchTerms: [ "design" ]
        }, {
            title: "far fa-object-ungroup",
            searchTerms: [ "design" ]
        }, {
            title: "fab fa-odnoklassniki",
            searchTerms: []
        }, {
            title: "fab fa-odnoklassniki-square",
            searchTerms: []
        }, {
            title: "fab fa-opencart",
            searchTerms: []
        }, {
            title: "fab fa-openid",
            searchTerms: []
        }, {
            title: "fab fa-opera",
            searchTerms: []
        }, {
            title: "fab fa-optin-monster",
            searchTerms: []
        }, {
            title: "fab fa-osi",
            searchTerms: []
        }, {
            title: "fas fa-outdent",
            searchTerms: []
        }, {
            title: "fab fa-page4",
            searchTerms: []
        }, {
            title: "fab fa-pagelines",
            searchTerms: [ "leaf", "leaves", "tree", "plant", "eco", "nature" ]
        }, {
            title: "fas fa-paint-brush",
            searchTerms: []
        }, {
            title: "fab fa-palfed",
            searchTerms: []
        }, {
            title: "fas fa-pallet",
            searchTerms: []
        }, {
            title: "fas fa-paper-plane",
            searchTerms: []
        }, {
            title: "far fa-paper-plane",
            searchTerms: []
        }, {
            title: "fas fa-paperclip",
            searchTerms: [ "attachment" ]
        }, {
            title: "fas fa-paragraph",
            searchTerms: []
        }, {
            title: "fas fa-paste",
            searchTerms: [ "copy", "clipboard" ]
        }, {
            title: "fab fa-patreon",
            searchTerms: []
        }, {
            title: "fas fa-pause",
            searchTerms: [ "wait" ]
        }, {
            title: "fas fa-pause-circle",
            searchTerms: []
        }, {
            title: "far fa-pause-circle",
            searchTerms: []
        }, {
            title: "fas fa-paw",
            searchTerms: [ "pet" ]
        }, {
            title: "fab fa-paypal",
            searchTerms: []
        }, {
            title: "fas fa-pen-square",
            searchTerms: [ "write", "edit", "update", "pencil-square" ]
        }, {
            title: "fas fa-pencil-alt",
            searchTerms: [ "write", "edit", "update", "pencil", "design" ]
        }, {
            title: "fas fa-percent",
            searchTerms: []
        }, {
            title: "fab fa-periscope",
            searchTerms: []
        }, {
            title: "fab fa-phabricator",
            searchTerms: []
        }, {
            title: "fab fa-phoenix-framework",
            searchTerms: []
        }, {
            title: "fas fa-phone",
            searchTerms: [ "call", "voice", "number", "support", "earphone", "telephone" ]
        }, {
            title: "fas fa-phone-square",
            searchTerms: [ "call", "voice", "number", "support", "telephone" ]
        }, {
            title: "fas fa-phone-volume",
            searchTerms: [ "telephone", "volume-control-phone" ]
        }, {
            title: "fab fa-php",
            searchTerms: []
        }, {
            title: "fab fa-pied-piper",
            searchTerms: []
        }, {
            title: "fab fa-pied-piper-alt",
            searchTerms: []
        }, {
            title: "fab fa-pied-piper-pp",
            searchTerms: []
        }, {
            title: "fas fa-pills",
            searchTerms: [ "medicine", "drugs" ]
        }, {
            title: "fab fa-pinterest",
            searchTerms: []
        }, {
            title: "fab fa-pinterest-p",
            searchTerms: []
        }, {
            title: "fab fa-pinterest-square",
            searchTerms: []
        }, {
            title: "fas fa-plane",
            searchTerms: [ "travel", "trip", "location", "destination", "airplane", "fly", "mode" ]
        }, {
            title: "fas fa-play",
            searchTerms: [ "start", "playing", "music", "sound" ]
        }, {
            title: "fas fa-play-circle",
            searchTerms: [ "start", "playing" ]
        }, {
            title: "far fa-play-circle",
            searchTerms: [ "start", "playing" ]
        }, {
            title: "fab fa-playstation",
            searchTerms: []
        }, {
            title: "fas fa-plug",
            searchTerms: [ "power", "connect" ]
        }, {
            title: "fas fa-plus",
            searchTerms: [ "add", "new", "create", "expand" ]
        }, {
            title: "fas fa-plus-circle",
            searchTerms: [ "add", "new", "create", "expand" ]
        }, {
            title: "fas fa-plus-square",
            searchTerms: [ "add", "new", "create", "expand" ]
        }, {
            title: "far fa-plus-square",
            searchTerms: [ "add", "new", "create", "expand" ]
        }, {
            title: "fas fa-podcast",
            searchTerms: []
        }, {
            title: "fas fa-pound-sign",
            searchTerms: [ "gbp", "gbp" ]
        }, {
            title: "fas fa-power-off",
            searchTerms: [ "on" ]
        }, {
            title: "fas fa-print",
            searchTerms: []
        }, {
            title: "fab fa-product-hunt",
            searchTerms: []
        }, {
            title: "fab fa-pushed",
            searchTerms: []
        }, {
            title: "fas fa-puzzle-piece",
            searchTerms: [ "addon", "add-on", "section" ]
        }, {
            title: "fab fa-python",
            searchTerms: []
        }, {
            title: "fab fa-qq",
            searchTerms: []
        }, {
            title: "fas fa-qrcode",
            searchTerms: [ "scan" ]
        }, {
            title: "fas fa-question",
            searchTerms: [ "help", "information", "unknown", "support" ]
        }, {
            title: "fas fa-question-circle",
            searchTerms: [ "help", "information", "unknown", "support" ]
        }, {
            title: "far fa-question-circle",
            searchTerms: [ "help", "information", "unknown", "support" ]
        }, {
            title: "fas fa-quidditch",
            searchTerms: []
        }, {
            title: "fab fa-quinscape",
            searchTerms: []
        }, {
            title: "fab fa-quora",
            searchTerms: []
        }, {
            title: "fas fa-quote-left",
            searchTerms: []
        }, {
            title: "fas fa-quote-right",
            searchTerms: []
        }, {
            title: "fas fa-random",
            searchTerms: [ "sort", "shuffle" ]
        }, {
            title: "fab fa-ravelry",
            searchTerms: []
        }, {
            title: "fab fa-react",
            searchTerms: []
        }, {
            title: "fab fa-rebel",
            searchTerms: []
        }, {
            title: "fas fa-recycle",
            searchTerms: []
        }, {
            title: "fab fa-red-river",
            searchTerms: []
        }, {
            title: "fab fa-reddit",
            searchTerms: []
        }, {
            title: "fab fa-reddit-alien",
            searchTerms: []
        }, {
            title: "fab fa-reddit-square",
            searchTerms: []
        }, {
            title: "fas fa-redo",
            searchTerms: [ "forward", "repeat", "repeat" ]
        }, {
            title: "fas fa-redo-alt",
            searchTerms: [ "forward", "repeat" ]
        }, {
            title: "fas fa-registered",
            searchTerms: []
        }, {
            title: "far fa-registered",
            searchTerms: []
        }, {
            title: "fab fa-rendact",
            searchTerms: []
        }, {
            title: "fab fa-renren",
            searchTerms: []
        }, {
            title: "fas fa-reply",
            searchTerms: []
        }, {
            title: "fas fa-reply-all",
            searchTerms: []
        }, {
            title: "fab fa-replyd",
            searchTerms: []
        }, {
            title: "fab fa-resolving",
            searchTerms: []
        }, {
            title: "fas fa-retweet",
            searchTerms: [ "refresh", "reload", "share", "swap" ]
        }, {
            title: "fas fa-road",
            searchTerms: [ "street" ]
        }, {
            title: "fas fa-rocket",
            searchTerms: [ "app" ]
        }, {
            title: "fab fa-rocketchat",
            searchTerms: []
        }, {
            title: "fab fa-rockrms",
            searchTerms: []
        }, {
            title: "fas fa-rss",
            searchTerms: [ "blog" ]
        }, {
            title: "fas fa-rss-square",
            searchTerms: [ "feed", "blog" ]
        }, {
            title: "fas fa-ruble-sign",
            searchTerms: [ "rub", "rub" ]
        }, {
            title: "fas fa-rupee-sign",
            searchTerms: [ "indian", "inr" ]
        }, {
            title: "fab fa-safari",
            searchTerms: [ "browser" ]
        }, {
            title: "fab fa-sass",
            searchTerms: []
        }, {
            title: "fas fa-save",
            searchTerms: [ "floppy", "floppy-o" ]
        }, {
            title: "far fa-save",
            searchTerms: [ "floppy", "floppy-o" ]
        }, {
            title: "fab fa-schlix",
            searchTerms: []
        }, {
            title: "fab fa-scribd",
            searchTerms: []
        }, {
            title: "fas fa-search",
            searchTerms: [ "magnify", "zoom", "enlarge", "bigger" ]
        }, {
            title: "fas fa-search-minus",
            searchTerms: [ "magnify", "minify", "zoom", "smaller" ]
        }, {
            title: "fas fa-search-plus",
            searchTerms: [ "magnify", "zoom", "enlarge", "bigger" ]
        }, {
            title: "fab fa-searchengin",
            searchTerms: []
        }, {
            title: "fab fa-sellcast",
            searchTerms: [ "eercast" ]
        }, {
            title: "fab fa-sellsy",
            searchTerms: []
        }, {
            title: "fas fa-server",
            searchTerms: []
        }, {
            title: "fab fa-servicestack",
            searchTerms: []
        }, {
            title: "fas fa-share",
            searchTerms: []
        }, {
            title: "fas fa-share-alt",
            searchTerms: []
        }, {
            title: "fas fa-share-alt-square",
            searchTerms: []
        }, {
            title: "fas fa-share-square",
            searchTerms: [ "social", "send" ]
        }, {
            title: "far fa-share-square",
            searchTerms: [ "social", "send" ]
        }, {
            title: "fas fa-shekel-sign",
            searchTerms: [ "ils", "ils" ]
        }, {
            title: "fas fa-shield-alt",
            searchTerms: [ "shield" ]
        }, {
            title: "fas fa-ship",
            searchTerms: [ "boat", "sea" ]
        }, {
            title: "fas fa-shipping-fast",
            searchTerms: []
        }, {
            title: "fab fa-shirtsinbulk",
            searchTerms: []
        }, {
            title: "fas fa-shopping-bag",
            searchTerms: []
        }, {
            title: "fas fa-shopping-basket",
            searchTerms: []
        }, {
            title: "fas fa-shopping-cart",
            searchTerms: [ "checkout", "buy", "purchase", "payment" ]
        }, {
            title: "fas fa-shower",
            searchTerms: []
        }, {
            title: "fas fa-sign-in-alt",
            searchTerms: [ "enter", "join", "log in", "login", "sign up", "sign in", "signin", "signup", "arrow", "sign-in" ]
        }, {
            title: "fas fa-sign-language",
            searchTerms: []
        }, {
            title: "fas fa-sign-out-alt",
            searchTerms: [ "log out", "logout", "leave", "exit", "arrow", "sign-out" ]
        }, {
            title: "fas fa-signal",
            searchTerms: [ "graph", "bars", "status" ]
        }, {
            title: "fab fa-simplybuilt",
            searchTerms: []
        }, {
            title: "fab fa-sistrix",
            searchTerms: []
        }, {
            title: "fas fa-sitemap",
            searchTerms: [ "directory", "hierarchy", "organization" ]
        }, {
            title: "fab fa-skyatlas",
            searchTerms: []
        }, {
            title: "fab fa-skype",
            searchTerms: []
        }, {
            title: "fab fa-slack",
            searchTerms: [ "hashtag", "anchor", "hash" ]
        }, {
            title: "fab fa-slack-hash",
            searchTerms: [ "hashtag", "anchor", "hash" ]
        }, {
            title: "fas fa-sliders-h",
            searchTerms: [ "settings", "sliders" ]
        }, {
            title: "fab fa-slideshare",
            searchTerms: []
        }, {
            title: "fas fa-smile",
            searchTerms: [ "face", "emoticon", "happy", "approve", "satisfied", "rating" ]
        }, {
            title: "far fa-smile",
            searchTerms: [ "face", "emoticon", "happy", "approve", "satisfied", "rating" ]
        }, {
            title: "fab fa-snapchat",
            searchTerms: []
        }, {
            title: "fab fa-snapchat-ghost",
            searchTerms: []
        }, {
            title: "fab fa-snapchat-square",
            searchTerms: []
        }, {
            title: "fas fa-snowflake",
            searchTerms: []
        }, {
            title: "far fa-snowflake",
            searchTerms: []
        }, {
            title: "fas fa-sort",
            searchTerms: [ "order" ]
        }, {
            title: "fas fa-sort-alpha-down",
            searchTerms: [ "sort-alpha-asc" ]
        }, {
            title: "fas fa-sort-alpha-up",
            searchTerms: [ "sort-alpha-desc" ]
        }, {
            title: "fas fa-sort-amount-down",
            searchTerms: [ "sort-amount-asc" ]
        }, {
            title: "fas fa-sort-amount-up",
            searchTerms: [ "sort-amount-desc" ]
        }, {
            title: "fas fa-sort-down",
            searchTerms: [ "arrow", "descending", "sort-desc" ]
        }, {
            title: "fas fa-sort-numeric-down",
            searchTerms: [ "numbers", "sort-numeric-asc" ]
        }, {
            title: "fas fa-sort-numeric-up",
            searchTerms: [ "numbers", "sort-numeric-desc" ]
        }, {
            title: "fas fa-sort-up",
            searchTerms: [ "arrow", "ascending", "sort-asc" ]
        }, {
            title: "fab fa-soundcloud",
            searchTerms: []
        }, {
            title: "fas fa-space-shuttle",
            searchTerms: []
        }, {
            title: "fab fa-speakap",
            searchTerms: []
        }, {
            title: "fas fa-spinner",
            searchTerms: [ "loading", "progress" ]
        }, {
            title: "fab fa-spotify",
            searchTerms: []
        }, {
            title: "fas fa-square",
            searchTerms: [ "block", "box" ]
        }, {
            title: "far fa-square",
            searchTerms: [ "block", "box" ]
        }, {
            title: "fas fa-square-full",
            searchTerms: []
        }, {
            title: "fab fa-stack-exchange",
            searchTerms: []
        }, {
            title: "fab fa-stack-overflow",
            searchTerms: []
        }, {
            title: "fas fa-star",
            searchTerms: [ "award", "achievement", "night", "rating", "score", "favorite" ]
        }, {
            title: "far fa-star",
            searchTerms: [ "award", "achievement", "night", "rating", "score", "favorite" ]
        }, {
            title: "fas fa-star-half",
            searchTerms: [ "award", "achievement", "rating", "score", "star-half-empty", "star-half-full" ]
        }, {
            title: "far fa-star-half",
            searchTerms: [ "award", "achievement", "rating", "score", "star-half-empty", "star-half-full" ]
        }, {
            title: "fab fa-staylinked",
            searchTerms: []
        }, {
            title: "fab fa-steam",
            searchTerms: []
        }, {
            title: "fab fa-steam-square",
            searchTerms: []
        }, {
            title: "fab fa-steam-symbol",
            searchTerms: []
        }, {
            title: "fas fa-step-backward",
            searchTerms: [ "rewind", "previous", "beginning", "start", "first" ]
        }, {
            title: "fas fa-step-forward",
            searchTerms: [ "next", "end", "last" ]
        }, {
            title: "fas fa-stethoscope",
            searchTerms: []
        }, {
            title: "fab fa-sticker-mule",
            searchTerms: []
        }, {
            title: "fas fa-sticky-note",
            searchTerms: []
        }, {
            title: "far fa-sticky-note",
            searchTerms: []
        }, {
            title: "fas fa-stop",
            searchTerms: [ "block", "box", "square" ]
        }, {
            title: "fas fa-stop-circle",
            searchTerms: []
        }, {
            title: "far fa-stop-circle",
            searchTerms: []
        }, {
            title: "fas fa-stopwatch",
            searchTerms: [ "time" ]
        }, {
            title: "fab fa-strava",
            searchTerms: []
        }, {
            title: "fas fa-street-view",
            searchTerms: [ "map" ]
        }, {
            title: "fas fa-strikethrough",
            searchTerms: []
        }, {
            title: "fab fa-stripe",
            searchTerms: []
        }, {
            title: "fab fa-stripe-s",
            searchTerms: []
        }, {
            title: "fab fa-studiovinari",
            searchTerms: []
        }, {
            title: "fab fa-stumbleupon",
            searchTerms: []
        }, {
            title: "fab fa-stumbleupon-circle",
            searchTerms: []
        }, {
            title: "fas fa-subscript",
            searchTerms: []
        }, {
            title: "fas fa-subway",
            searchTerms: []
        }, {
            title: "fas fa-suitcase",
            searchTerms: [ "trip", "luggage", "travel", "move", "baggage" ]
        }, {
            title: "fas fa-sun",
            searchTerms: [ "weather", "contrast", "lighter", "brighten", "day" ]
        }, {
            title: "far fa-sun",
            searchTerms: [ "weather", "contrast", "lighter", "brighten", "day" ]
        }, {
            title: "fab fa-superpowers",
            searchTerms: []
        }, {
            title: "fas fa-superscript",
            searchTerms: [ "exponential" ]
        }, {
            title: "fab fa-supple",
            searchTerms: []
        }, {
            title: "fas fa-sync",
            searchTerms: [ "reload", "refresh", "refresh" ]
        }, {
            title: "fas fa-sync-alt",
            searchTerms: [ "reload", "refresh" ]
        }, {
            title: "fas fa-syringe",
            searchTerms: [ "immunizations", "needle" ]
        }, {
            title: "fas fa-table",
            searchTerms: [ "data", "excel", "spreadsheet" ]
        }, {
            title: "fas fa-table-tennis",
            searchTerms: []
        }, {
            title: "fas fa-tablet",
            searchTerms: [ "ipad", "device" ]
        }, {
            title: "fas fa-tablet-alt",
            searchTerms: [ "tablet" ]
        }, {
            title: "fas fa-tachometer-alt",
            searchTerms: [ "tachometer", "dashboard" ]
        }, {
            title: "fas fa-tag",
            searchTerms: [ "label" ]
        }, {
            title: "fas fa-tags",
            searchTerms: [ "labels" ]
        }, {
            title: "fas fa-tasks",
            searchTerms: [ "progress", "loading", "downloading", "downloads", "settings" ]
        }, {
            title: "fas fa-taxi",
            searchTerms: [ "vehicle" ]
        }, {
            title: "fab fa-telegram",
            searchTerms: []
        }, {
            title: "fab fa-telegram-plane",
            searchTerms: []
        }, {
            title: "fab fa-tencent-weibo",
            searchTerms: []
        }, {
            title: "fas fa-terminal",
            searchTerms: [ "command", "prompt", "code" ]
        }, {
            title: "fas fa-text-height",
            searchTerms: []
        }, {
            title: "fas fa-text-width",
            searchTerms: []
        }, {
            title: "fas fa-th",
            searchTerms: [ "blocks", "squares", "boxes", "grid" ]
        }, {
            title: "fas fa-th-large",
            searchTerms: [ "blocks", "squares", "boxes", "grid" ]
        }, {
            title: "fas fa-th-list",
            searchTerms: [ "ul", "ol", "checklist", "finished", "completed", "done", "todo" ]
        }, {
            title: "fab fa-themeisle",
            searchTerms: []
        }, {
            title: "fas fa-thermometer",
            searchTerms: [ "temperature", "fever" ]
        }, {
            title: "fas fa-thermometer-empty",
            searchTerms: [ "status" ]
        }, {
            title: "fas fa-thermometer-full",
            searchTerms: [ "status" ]
        }, {
            title: "fas fa-thermometer-half",
            searchTerms: [ "status" ]
        }, {
            title: "fas fa-thermometer-quarter",
            searchTerms: [ "status" ]
        }, {
            title: "fas fa-thermometer-three-quarters",
            searchTerms: [ "status" ]
        }, {
            title: "fas fa-thumbs-down",
            searchTerms: [ "dislike", "disapprove", "disagree", "hand", "thumbs-o-down" ]
        }, {
            title: "far fa-thumbs-down",
            searchTerms: [ "dislike", "disapprove", "disagree", "hand", "thumbs-o-down" ]
        }, {
            title: "fas fa-thumbs-up",
            searchTerms: [ "like", "favorite", "approve", "agree", "hand", "thumbs-o-up" ]
        }, {
            title: "far fa-thumbs-up",
            searchTerms: [ "like", "favorite", "approve", "agree", "hand", "thumbs-o-up" ]
        }, {
            title: "fas fa-thumbtack",
            searchTerms: [ "marker", "pin", "location", "coordinates", "thumb-tack" ]
        }, {
            title: "fas fa-ticket-alt",
            searchTerms: [ "ticket" ]
        }, {
            title: "fas fa-times",
            searchTerms: [ "close", "exit", "x", "cross" ]
        }, {
            title: "fas fa-times-circle",
            searchTerms: [ "close", "exit", "x" ]
        }, {
            title: "far fa-times-circle",
            searchTerms: [ "close", "exit", "x" ]
        }, {
            title: "fas fa-tint",
            searchTerms: [ "raindrop", "waterdrop", "drop", "droplet" ]
        }, {
            title: "fas fa-toggle-off",
            searchTerms: [ "switch" ]
        }, {
            title: "fas fa-toggle-on",
            searchTerms: [ "switch" ]
        }, {
            title: "fas fa-trademark",
            searchTerms: []
        }, {
            title: "fas fa-train",
            searchTerms: []
        }, {
            title: "fas fa-transgender",
            searchTerms: [ "intersex" ]
        }, {
            title: "fas fa-transgender-alt",
            searchTerms: []
        }, {
            title: "fas fa-trash",
            searchTerms: [ "garbage", "delete", "remove", "hide" ]
        }, {
            title: "fas fa-trash-alt",
            searchTerms: [ "garbage", "delete", "remove", "hide", "trash", "trash-o" ]
        }, {
            title: "far fa-trash-alt",
            searchTerms: [ "garbage", "delete", "remove", "hide", "trash", "trash-o" ]
        }, {
            title: "fas fa-tree",
            searchTerms: []
        }, {
            title: "fab fa-trello",
            searchTerms: []
        }, {
            title: "fab fa-tripadvisor",
            searchTerms: []
        }, {
            title: "fas fa-trophy",
            searchTerms: [ "award", "achievement", "cup", "winner", "game" ]
        }, {
            title: "fas fa-truck",
            searchTerms: [ "shipping" ]
        }, {
            title: "fas fa-tty",
            searchTerms: []
        }, {
            title: "fab fa-tumblr",
            searchTerms: []
        }, {
            title: "fab fa-tumblr-square",
            searchTerms: []
        }, {
            title: "fas fa-tv",
            searchTerms: [ "display", "computer", "monitor", "television" ]
        }, {
            title: "fab fa-twitch",
            searchTerms: []
        }, {
            title: "fab fa-twitter",
            searchTerms: [ "tweet", "social network" ]
        }, {
            title: "fab fa-twitter-square",
            searchTerms: [ "tweet", "social network" ]
        }, {
            title: "fab fa-typo3",
            searchTerms: []
        }, {
            title: "fab fa-uber",
            searchTerms: []
        }, {
            title: "fab fa-uikit",
            searchTerms: []
        }, {
            title: "fas fa-umbrella",
            searchTerms: []
        }, {
            title: "fas fa-underline",
            searchTerms: []
        }, {
            title: "fas fa-undo",
            searchTerms: [ "back" ]
        }, {
            title: "fas fa-undo-alt",
            searchTerms: [ "back" ]
        }, {
            title: "fab fa-uniregistry",
            searchTerms: []
        }, {
            title: "fas fa-universal-access",
            searchTerms: []
        }, {
            title: "fas fa-university",
            searchTerms: [ "bank", "institution" ]
        }, {
            title: "fas fa-unlink",
            searchTerms: [ "remove", "chain", "chain-broken" ]
        }, {
            title: "fas fa-unlock",
            searchTerms: [ "protect", "admin", "password", "lock" ]
        }, {
            title: "fas fa-unlock-alt",
            searchTerms: [ "protect", "admin", "password", "lock" ]
        }, {
            title: "fab fa-untappd",
            searchTerms: []
        }, {
            title: "fas fa-upload",
            searchTerms: [ "import" ]
        }, {
            title: "fab fa-usb",
            searchTerms: []
        }, {
            title: "fas fa-user",
            searchTerms: [ "person", "man", "head", "profile", "account" ]
        }, {
            title: "far fa-user",
            searchTerms: [ "person", "man", "head", "profile", "account" ]
        }, {
            title: "fas fa-user-circle",
            searchTerms: [ "person", "man", "head", "profile", "account" ]
        }, {
            title: "far fa-user-circle",
            searchTerms: [ "person", "man", "head", "profile", "account" ]
        }, {
            title: "fas fa-user-md",
            searchTerms: [ "doctor", "profile", "medical", "nurse", "job", "occupation" ]
        }, {
            title: "fas fa-user-plus",
            searchTerms: [ "sign up", "signup" ]
        }, {
            title: "fas fa-user-secret",
            searchTerms: [ "whisper", "spy", "incognito", "privacy" ]
        }, {
            title: "fas fa-user-times",
            searchTerms: []
        }, {
            title: "fas fa-users",
            searchTerms: [ "people", "profiles", "persons" ]
        }, {
            title: "fab fa-ussunnah",
            searchTerms: []
        }, {
            title: "fas fa-utensil-spoon",
            searchTerms: [ "spoon" ]
        }, {
            title: "fas fa-utensils",
            searchTerms: [ "food", "restaurant", "spoon", "knife", "dinner", "eat", "cutlery" ]
        }, {
            title: "fab fa-vaadin",
            searchTerms: []
        }, {
            title: "fas fa-venus",
            searchTerms: [ "female" ]
        }, {
            title: "fas fa-venus-double",
            searchTerms: []
        }, {
            title: "fas fa-venus-mars",
            searchTerms: []
        }, {
            title: "fab fa-viacoin",
            searchTerms: []
        }, {
            title: "fab fa-viadeo",
            searchTerms: []
        }, {
            title: "fab fa-viadeo-square",
            searchTerms: []
        }, {
            title: "fab fa-viber",
            searchTerms: []
        }, {
            title: "fas fa-video",
            searchTerms: [ "film", "movie", "record", "camera", "video-camera" ]
        }, {
            title: "fab fa-vimeo",
            searchTerms: []
        }, {
            title: "fab fa-vimeo-square",
            searchTerms: []
        }, {
            title: "fab fa-vimeo-v",
            searchTerms: [ "vimeo" ]
        }, {
            title: "fab fa-vine",
            searchTerms: []
        }, {
            title: "fab fa-vk",
            searchTerms: []
        }, {
            title: "fab fa-vnv",
            searchTerms: []
        }, {
            title: "fas fa-volleyball-ball",
            searchTerms: []
        }, {
            title: "fas fa-volume-down",
            searchTerms: [ "audio", "lower", "quieter", "sound", "music" ]
        }, {
            title: "fas fa-volume-off",
            searchTerms: [ "audio", "mute", "sound", "music" ]
        }, {
            title: "fas fa-volume-up",
            searchTerms: [ "audio", "higher", "louder", "sound", "music" ]
        }, {
            title: "fab fa-vuejs",
            searchTerms: []
        }, {
            title: "fas fa-warehouse",
            searchTerms: []
        }, {
            title: "fab fa-weibo",
            searchTerms: []
        }, {
            title: "fas fa-weight",
            searchTerms: [ "scale" ]
        }, {
            title: "fab fa-weixin",
            searchTerms: []
        }, {
            title: "fab fa-whatsapp",
            searchTerms: []
        }, {
            title: "fab fa-whatsapp-square",
            searchTerms: []
        }, {
            title: "fas fa-wheelchair",
            searchTerms: [ "handicap", "person" ]
        }, {
            title: "fab fa-whmcs",
            searchTerms: []
        }, {
            title: "fas fa-wifi",
            searchTerms: []
        }, {
            title: "fab fa-wikipedia-w",
            searchTerms: []
        }, {
            title: "fas fa-window-close",
            searchTerms: []
        }, {
            title: "far fa-window-close",
            searchTerms: []
        }, {
            title: "fas fa-window-maximize",
            searchTerms: []
        }, {
            title: "far fa-window-maximize",
            searchTerms: []
        }, {
            title: "fas fa-window-minimize",
            searchTerms: []
        }, {
            title: "far fa-window-minimize",
            searchTerms: []
        }, {
            title: "fas fa-window-restore",
            searchTerms: []
        }, {
            title: "far fa-window-restore",
            searchTerms: []
        }, {
            title: "fab fa-windows",
            searchTerms: [ "microsoft" ]
        }, {
            title: "fas fa-won-sign",
            searchTerms: [ "krw", "krw" ]
        }, {
            title: "fab fa-wordpress",
            searchTerms: []
        }, {
            title: "fab fa-wordpress-simple",
            searchTerms: []
        }, {
            title: "fab fa-wpbeginner",
            searchTerms: []
        }, {
            title: "fab fa-wpexplorer",
            searchTerms: []
        }, {
            title: "fab fa-wpforms",
            searchTerms: []
        }, {
            title: "fas fa-wrench",
            searchTerms: [ "settings", "fix", "update", "spanner", "tool" ]
        }, {
            title: "fab fa-xbox",
            searchTerms: []
        }, {
            title: "fab fa-xing",
            searchTerms: []
        }, {
            title: "fab fa-xing-square",
            searchTerms: []
        }, {
            title: "fab fa-y-combinator",
            searchTerms: []
        }, {
            title: "fab fa-yahoo",
            searchTerms: []
        }, {
            title: "fab fa-yandex",
            searchTerms: []
        }, {
            title: "fab fa-yandex-international",
            searchTerms: []
        }, {
            title: "fab fa-yelp",
            searchTerms: []
        }, {
            title: "fas fa-yen-sign",
            searchTerms: [ "jpy", "jpy" ]
        }, {
            title: "fab fa-yoast",
            searchTerms: []
        }, {
            title: "fab fa-youtube",
            searchTerms: [ "video", "film", "youtube-play", "youtube-square" ]
        }, {
            title: "fab fa-youtube-square",
            searchTerms: []
        } ]
    });
});