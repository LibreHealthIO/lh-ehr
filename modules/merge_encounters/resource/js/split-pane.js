/*!

Split Pane v0.9.3

Copyright (c) 2014 - 2016 Simon Hagström

Released under the MIT license
https://raw.github.com/shagstrom/split-pane/master/LICENSE

*/

(function ($) {

	'use strict';

	var methods = {};

	methods.init = function() {
		var $splitPanes = this;
		$splitPanes.each(setMinHeightAndMinWidth);
		$splitPanes.children('.split-pane-divider').html('<div class="split-pane-divider-inner"></div>');
		$splitPanes.children('.split-pane-divider').on('touchstart mousedown', mousedownHandler);
		setTimeout(function() {
			// Doing this later because of an issue with Chrome (v23.0.1271.64) returning split-pane width = 0
			// and triggering multiple resize events when page is being opened from an <a target="_blank"> .
			$splitPanes.each(attachResizeHandler);
			$(window).trigger('resize');
		}, 100);
	};

	methods.firstComponentSize = function(value) {
		this.each(function() {
			var $splitPane = $(this),
				components = getComponents($splitPane);
			if ($splitPane.is('.fixed-top')) {
				fixedTopHandler(components, components.divider.offsetTop)({pageY: value});
			} else if ($splitPane.is('.fixed-bottom')) {
				value = components.splitPane.offsetHeight -components.divider.offsetHeight - value;
				fixedBottomHandler(components, -components.last.offsetHeight)({pageY: -value});
			} else if ($splitPane.is('.horizontal-percent')) {
				value = components.splitPane.offsetHeight -components.divider.offsetHeight - value;
				horizontalPercentHandler(components, -components.last.offsetHeight)({pageY: -value});
			} else if ($splitPane.is('.fixed-left')) {
				fixedLeftHandler(components, components.divider.offsetLeft)({pageX: value});
			} else if ($splitPane.is('.fixed-right')) {
				value = components.splitPane.offsetWidth -components.divider.offsetWidth - value;
				fixedRightHandler(components, -components.last.offsetWidth)({pageX: -value});
			} else if ($splitPane.is('.vertical-percent')) {
				value = components.splitPane.offsetWidth -components.divider.offsetWidth - value;
				verticalPercentHandler(components, -components.last.offsetWidth)({pageX: -value});
			}
		});
	};

	methods.lastComponentSize = function(value) {
		this.each(function() {
			var $splitPane = $(this),
				components = getComponents($splitPane);
			if ($splitPane.is('.fixed-top')) {
				value = components.splitPane.offsetHeight -components.divider.offsetHeight - value;
				fixedTopHandler(components, components.divider.offsetTop)({pageY: value});
			} else if ($splitPane.is('.fixed-bottom')) {
				fixedBottomHandler(components, -components.last.offsetHeight)({pageY: -value});
			} else if ($splitPane.is('.horizontal-percent')) {
				horizontalPercentHandler(components, -components.last.offsetHeight)({pageY: -value});
			} else if ($splitPane.is('.fixed-left')) {
				value = components.splitPane.offsetWidth -components.divider.offsetWidth - value;
				fixedLeftHandler(components, components.divider.offsetLeft)({pageX: value});
			} else if ($splitPane.is('.fixed-right')) {
				fixedRightHandler(components, -components.last.offsetWidth)({pageX: -value});
			} else if ($splitPane.is('.vertical-percent')) {
				verticalPercentHandler(components, -components.last.offsetWidth)({pageX: -value});
			}
		});
	};

	$.fn.splitPane = function(method) {
		methods[method || 'init'].apply(this, $.grep(arguments, function(it, i) { return i > 0; }));
	};

	function setMinHeightAndMinWidth() {
		var $splitPane = $(this),
			components = getComponents($splitPane);
		if ($splitPane.is('.fixed-top, .fixed-bottom, .horizontal-percent')) {
			$splitPane.css('min-height', (minHeight(components.first) + minHeight(components.last) + $(components.divider).height()) + 'px');
		} else {
			$splitPane.css('min-width', (minWidth(components.first) + minWidth(components.last) + $(components.divider).width()) + 'px');
		}
	}

	function mousedownHandler(event) {
		event.preventDefault();
		var $divider = $(this),
			$splitPane = $divider.parent();
		$divider.addClass('dragged');
		if (event.type.match(/^touch/)) {
			$divider.addClass('touch');
		}
		var moveEventHandler = createMousemove($splitPane, pageXof(event), pageYof(event));
		$(document).on('touchmove mousemove', moveEventHandler);
		$(document).one('touchend mouseup', function(event) {
			$(document).off('touchmove mousemove', moveEventHandler);
			$divider.removeClass('dragged touch');
			$splitPane.trigger('dividerdragend', [ getComponentsSizes($splitPane) ]);
		});
		$splitPane.trigger('dividerdragstart', [ getComponentsSizes($splitPane) ]);
	}

	function getComponentsSizes($splitPane) {
		var property = $splitPane.is('.fixed-top, .fixed-bottom, .horizontal-percent') ?
			'height' : 'width';
		return {
			firstComponentSize: $splitPane.find('.split-pane-component:first')[property](),
			lastComponentSize: $splitPane.find('.split-pane-component:last')[property]()
		};
	}

	function attachResizeHandler() {
		var $splitPane = $(this),
			internalHandler = createParentresizeHandler($splitPane),
			parent = $splitPane.parent().closest('.split-pane')[0] || window;
		$(parent).on(parent === window ? 'resize' : 'splitpaneresize', function(event) {
			var target = event.target === document ? window : event.target;
			if (target === parent) {
				internalHandler(event);
			}
		});
	}

	function createParentresizeHandler($splitPane) {
		var components = getComponents($splitPane);
		if ($splitPane.is('.fixed-top')) {
			return function(event) {
				var lastComponentMinHeight = minHeight(components.last),
					maxfirstComponentHeight = components.splitPane.offsetHeight - lastComponentMinHeight -  components.divider.offsetHeight;
				if (components.first.offsetHeight > maxfirstComponentHeight) {
					setTop(components, maxfirstComponentHeight + 'px');
				}
				$splitPane.trigger('splitpaneresize');
			};
		} else if ($splitPane.is('.fixed-bottom')) {
			return function(event) {
				var firstComponentMinHeight = minHeight(components.first),
					maxLastComponentHeight = components.splitPane.offsetHeight - firstComponentMinHeight -  components.divider.offsetHeight;
				if (components.last.offsetHeight > maxLastComponentHeight) {
					setBottom(components, maxLastComponentHeight + 'px');
				}
				$splitPane.trigger('splitpaneresize');
			};
		} else if ($splitPane.is('.horizontal-percent')) {
			return function(event) {
				var lastComponentMinHeight = minHeight(components.last),
					firstComponentMinHeight = minHeight(components.first),
					maxLastComponentHeight = components.splitPane.offsetHeight - firstComponentMinHeight -  components.divider.offsetHeight;
				if (components.last.offsetHeight > maxLastComponentHeight) {
					setBottom(components, (maxLastComponentHeight / components.splitPane.offsetHeight * 100) + '%');
				} else {
					if (components.splitPane.offsetHeight - components.first.offsetHeight -  components.divider.offsetHeight < lastComponentMinHeight) {
						setBottom(components, (lastComponentMinHeight / components.splitPane.offsetHeight * 100) + '%');
					}
				}
				$splitPane.trigger('splitpaneresize');
			};
		} else if ($splitPane.is('.fixed-left')) {
			return function(event) {
				var lastComponentMinWidth = minWidth(components.last),
					maxFirstComponentWidth = components.splitPane.offsetWidth - lastComponentMinWidth -  components.divider.offsetWidth;
				if (components.first.offsetWidth > maxFirstComponentWidth) {
					setLeft(components, maxFirstComponentWidth + 'px');
				}
				$splitPane.trigger('splitpaneresize');
			};
		} else if ($splitPane.is('.fixed-right')) {
			return function(event) {
				var firstComponentMinWidth = minWidth(components.first),
					maxLastComponentWidth = components.splitPane.offsetWidth - firstComponentMinWidth -  components.divider.offsetWidth;
				if (components.last.offsetWidth > maxLastComponentWidth) {
					setRight(components, maxLastComponentWidth + 'px');
				}
				$splitPane.trigger('splitpaneresize');
			};
		} else if ($splitPane.is('.vertical-percent')) {
			return function(event) {
				var lastComponentMinWidth = minWidth(components.last),
					firstComponentMinWidth = minWidth(components.first),
					maxLastComponentWidth = components.splitPane.offsetWidth - firstComponentMinWidth -  components.divider.offsetWidth;
				if (components.last.offsetWidth > maxLastComponentWidth) {
					setRight(components, (maxLastComponentWidth / components.splitPane.offsetWidth * 100) + '%');
				} else {
					if (components.splitPane.offsetWidth - components.first.offsetWidth -  components.divider.offsetWidth < lastComponentMinWidth) {
						setRight(components, (lastComponentMinWidth / components.splitPane.offsetWidth * 100) + '%');
					}
				}
				$splitPane.trigger('splitpaneresize');
			};
		}
	}

	function createMousemove($splitPane, pageX, pageY) {
		var components = getComponents($splitPane);
		if ($splitPane.is('.fixed-top')) {
			return fixedTopHandler(components, pageY);
		} else if ($splitPane.is('.fixed-bottom')) {
			return fixedBottomHandler(components, pageY);
		} else if ($splitPane.is('.horizontal-percent')) {
			return horizontalPercentHandler(components, pageY);
		} else if ($splitPane.is('.fixed-left')) {
			return fixedLeftHandler(components, pageX);
		} else if ($splitPane.is('.fixed-right')) {
			return fixedRightHandler(components, pageX);
		} else if ($splitPane.is('.vertical-percent')) {
			return verticalPercentHandler(components, pageX);
		}
	}

	function fixedTopHandler(components, pageY) {
		var firstComponentMinHeight =  minHeight(components.first),
			maxFirstComponentHeight = components.splitPane.offsetHeight - minHeight(components.last) - components.divider.offsetHeight,
			topOffset = components.divider.offsetTop - pageY;
		return function(event) {
			event.preventDefault && event.preventDefault();
			var top = newTop(firstComponentMinHeight, maxFirstComponentHeight, topOffset + pageYof(event));
			setTop(components, top + 'px');
			$(components.splitPane).trigger('splitpaneresize');
		};
	}

	function fixedBottomHandler(components, pageY) {
		var lastComponentMinHeight = minHeight(components.last),
			maxLastComponentHeight = components.splitPane.offsetHeight - minHeight(components.first) - components.divider.offsetHeight,
			bottomOffset = components.last.offsetHeight + pageY;
		return function(event) {
			event.preventDefault && event.preventDefault();
			var bottom = Math.min(Math.max(lastComponentMinHeight, bottomOffset - pageYof(event)), maxLastComponentHeight);
			setBottom(components, bottom + 'px');
			$(components.splitPane).trigger('splitpaneresize');
		};
	}

	function horizontalPercentHandler(components, pageY) {
		var splitPaneHeight = components.splitPane.offsetHeight,
			lastComponentMinHeight = minHeight(components.last),
			maxLastComponentHeight = splitPaneHeight - minHeight(components.first) - components.divider.offsetHeight,
			bottomOffset = components.last.offsetHeight + pageY;
		return function(event) {
			event.preventDefault && event.preventDefault();
			var bottom = Math.min(Math.max(lastComponentMinHeight, bottomOffset - pageYof(event)), maxLastComponentHeight);
			setBottom(components, (bottom / splitPaneHeight * 100) + '%');
			$(components.splitPane).trigger('splitpaneresize');
		};
	}

	function fixedLeftHandler(components, pageX) {
		var firstComponentMinWidth = minWidth(components.first),
			maxFirstComponentWidth = components.splitPane.offsetWidth - minWidth(components.last) - components.divider.offsetWidth,
			leftOffset = components.divider.offsetLeft - pageX;
		return function(event) {
			event.preventDefault && event.preventDefault();
			var left = newLeft(firstComponentMinWidth, maxFirstComponentWidth, leftOffset + pageXof(event));
			setLeft(components, left + 'px');
			$(components.splitPane).trigger('splitpaneresize');
		};
	}

	function fixedRightHandler(components, pageX) {
		var lastComponentMinWidth = minWidth(components.last),
			maxLastComponentWidth = components.splitPane.offsetWidth - minWidth(components.first) - components.divider.offsetWidth,
			rightOffset = components.last.offsetWidth + pageX;
		return function(event) {
			event.preventDefault && event.preventDefault();
			var right = Math.min(Math.max(lastComponentMinWidth, rightOffset - pageXof(event)), maxLastComponentWidth);
			setRight(components, right + 'px');
			$(components.splitPane).trigger('splitpaneresize');
		};
	}

	function verticalPercentHandler(components, pageX) {
		var splitPaneWidth = components.splitPane.offsetWidth,
			lastComponentMinWidth = minWidth(components.last),
			maxLastComponentWidth = splitPaneWidth - minWidth(components.first) - components.divider.offsetWidth,
			rightOffset = components.last.offsetWidth + pageX;
		return function(event) {
			event.preventDefault && event.preventDefault();
			var right = Math.min(Math.max(lastComponentMinWidth, rightOffset - pageXof(event)), maxLastComponentWidth);
			setRight(components, (right / splitPaneWidth * 100) + '%');
			$(components.splitPane).trigger('splitpaneresize');
		};
	}

	function getComponents($splitPane) {
		return {
			splitPane: $splitPane[0],
			first: $splitPane.children('.split-pane-component:first')[0],
			divider: $splitPane.children('.split-pane-divider')[0],
			last: $splitPane.children('.split-pane-component:last')[0]
		};
	}

	function pageXof(event) {
		if (event.pageX !== undefined) {
			return event.pageX;
		} else if (event.originalEvent.pageX !== undefined) {
			return event.originalEvent.pageX;
		} else if (event.originalEvent.touches) {
			return event.originalEvent.touches[0].pageX;
		}
	}

	function pageYof(event) {
		if (event.pageY !== undefined) {
			return event.pageY;
		} else if (event.originalEvent.pageY !== undefined) {
			return event.originalEvent.pageY;
		} else if (event.originalEvent.touches) {
			return event.originalEvent.touches[0].pageY;
		}
	}

	function minHeight(element) {
		return parseInt($(element).css('min-height'), 10) || 0;
	}

	function minWidth(element) {
		return parseInt($(element).css('min-width'), 10) || 0;
	}

	function maxHeight(element) {
		return parseInt($(element).css('max-height'), 10);
	}

	function maxWidth(element) {
		return parseInt($(element).css('max-width'), 10);
	}

	function newTop(firstComponentMinHeight, maxFirstComponentHeight, value) {
		return Math.min(Math.max(firstComponentMinHeight, value), maxFirstComponentHeight);
	}

	function newLeft(firstComponentMinWidth, maxFirstComponentWidth, value) {
		return Math.min(Math.max(firstComponentMinWidth, value), maxFirstComponentWidth);
	}
	function setTop(components, top) {
		components.first.style.height = top;
		components.divider.style.top = top;
		components.last.style.top = top;
	}

	function setBottom(components, bottom) {
		components.first.style.bottom = bottom;
		components.divider.style.bottom = bottom;
		components.last.style.height = bottom;
	}

	function setLeft(components, left) {
		components.first.style.width = left;
		components.divider.style.left = left;
		components.last.style.left = left;
	}

	function setRight(components, right) {
		components.first.style.right = right;
		components.divider.style.right = right;
		components.last.style.width = right;
	}

})(jQuery);
