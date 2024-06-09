/*
 * MiniPreview v0.9
 *
 * @author  Will Boyd
 * Shared by Codegena, Edited by Corey Tyrone Hornbeck
 */

(function($) {
    var PREFIX = 'mini-preview';
    
    // implemented as a jQuery plugin
    $.fn.miniPreview = function(options) {
        return this.each(function() {
            var $this = $(this);
            var miniPreview = $this.data(PREFIX);
            if (miniPreview) {
                miniPreview.destroy();
            }

            miniPreview = new MiniPreview($this, options);
            miniPreview.generate();
            $this.data(PREFIX, miniPreview);
        });
    };
    
    var MiniPreview = function($el, options) {
        this.$el = $el;
        this.$el.addClass(PREFIX + '-anchor');
        this.options = $.extend({}, this.defaultOptions, options);
        this.counter = MiniPreview.prototype.sharedCounter++;
    };
    
    MiniPreview.prototype = {
        sharedCounter: 0,
        
        defaultOptions: {
            width: 256,
            height: 'auto', //overriden
            scale: .25,
            prefetch: 'pageload'
        },
                
        generate: function() {
            this.createElements();
            this.setPrefetch();
        },

        createElements: function() {
            var $wrapper = $('<div>', { class: PREFIX + '-wrapper' });
            var $loading = $('<div>', { class: PREFIX + '-loading' });
            var $frame = $('<img>', { class: PREFIX + '-frame' });
            var $cover = $('<div>', { class: PREFIX + '-cover' });
            $wrapper.appendTo(this.$el).append($loading, $frame, $cover);
            
            // sizing
            $wrapper.css({
                width: this.options.width + 'px',
                height: this.options.height
            });
            
            // scaling
            var inversePercent = 100 / this.options.scale;
            $frame.css({
                width: inversePercent + '%',
                height: inversePercent + '%',
                transform: 'scale(' + this.options.scale + ')'
            });

            // positioning
            var fontSize = parseInt(this.$el.css('font-size').replace('px', ''), 10)
            var top = (this.$el.height() + fontSize) / 2;
            var left = (this.$el.width() - $wrapper.outerWidth()) / 2 + 100;   //adding 100 at the end to prevent frames from going too far left offscreen
            $wrapper.css({
                top: top + 'px',
                left: left + 'px'
            });
        },
        
        setPrefetch: function() {
            switch (this.options.prefetch) {
                case 'pageload':
                    this.loadPreview();
                    break;
                case 'parenthover':
                    this.$el.parent().one(this.getNamespacedEvent('mouseenter'),
                        this.loadPreview.bind(this));
                    break;
                case 'none':
                    this.$el.one(this.getNamespacedEvent('mouseenter'),
                        this.loadPreview.bind(this));
                    break;
                default:
                    throw 'Prefetch setting not recognized: ' + this.options.prefetch;
                    break;
            }
        },
        
        loadPreview: function() {

            this.$el.find('.' + PREFIX + '-frame')
                .attr('src', this.$el.attr('image-url'))
                .on('load', function() {
                    //console.log($test);
                    // some sites don't set their background color
                    $(this).css('background-color', '#fff');

                    //wrapper needs to be devided by 4 due to presets of height and width being set to 400% for the frame class:
                    var oldHeight = parseFloat($(this).parent('.' + PREFIX + '-wrapper').css('height'));
                    $(this).parent('.' + PREFIX + '-wrapper').css('height', oldHeight/4);  //setting new height of wrapper class

                });

        },
        
        getNamespacedEvent: function(event) {
            return event + '.' + PREFIX + '_' + this.counter;
        },

        destroy: function() {
            this.$el.removeClass(PREFIX + '-anchor');
            this.$el.parent().off(this.getNamespacedEvent('mouseenter'));
            this.$el.off(this.getNamespacedEvent('mouseenter'));
            this.$el.find('.' + PREFIX + '-wrapper').remove();
        }
    };
})(jQuery);
