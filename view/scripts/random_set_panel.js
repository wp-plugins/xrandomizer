/**
 * Created by vagenas on 6/5/2015.
 */

(function ($) {
    "use strict";

    var extension = $xd_v141226_dev.$.extension_class('randomizer', '$ajax');
    var $menu_pages = window['$randomizer'].$menu_pages;

    $randomizer.$ajax = new extension();

    $randomizer.$ajax.getElementMarkup = function (setId, elemId, mode, content, pined, disabled, complete, beforeSend) {
        $randomizer.$ajax.get(
            '©ajax.®ajaxPrivateGetElementMarkup',
            $randomizer.$ajax.___private_type,
            [setId, elemId, mode, content, pined, disabled],
            {
                complete: complete,
                beforeSend: beforeSend
            }
        );
    };

    var setManager = function (setIdx) {
        this.setIdx = setIdx;
        this.selector = '#panel--random-set-' + this.setIdx;
    };

    setManager.prototype = {
        bindDeleteEvent: function () {
            $('.set-delete').unbind('click').click(function () {
                var r = confirm("Are you sure you want to delete this set?");
                if (r != true) {
                    return r;
                }
                var setIdx = parseInt($(this).attr('data-setidx'));
                var newSet = new setManager(setIdx);
                newSet.deleteSet();
            });
        },

        bindAddEvent: function () {
            $('.set-add').unbind('click').click(function () {
                var setIdx = parseInt($(this).attr('data-setidx'));
                var newSet = new setManager(setIdx);
                newSet.addNewSet();
            });
        },

        addNewSet: function () {
            setManager.prototype.bindAddEvent();
            setManager.prototype.bindDeleteEvent();
        },

        deleteSet: function () {
            $(this.selector).remove();
            setManager.prototype.bindAddEvent();
            setManager.prototype.bindDeleteEvent();
        }
    };

    var html = function (setIdx, idx) {
        this.setIdx = setIdx;
        this.idx = idx;
        this.mode = null;

        var $el = $(this.selector());
        if ($el.length) {
            this.pined = $(this.pinedSelector()).hasClass('active');
            this.disabled = $(this.disableSelector()).hasClass('active');
        } else {
            this.pined = false;
            this.disabled = false;
        }
        this.init();
    };

    html.prototype = {
        codeModes: {
            html: 'HTML',
            php: 'PHP',
            markdown: 'Markdown',
            javascript: 'Javascript',
            text: 'Text'
        },
        init: function () {},
        selector: function () {
            return '#element-row-random-set-' + this.setIdx + '-' + this.idx;
        },
        pinedSelector: function () {
            return this.selector() + ' .element-pin';
        },
        disableSelector: function () {
            return this.selector() + ' .element-disable';
        },
        elementAddSelector: function () {
            return this.selector() + ' .element-add';
        },
        elementDeleteSelector: function () {
            return this.selector() + ' .element-delete';
        },
        elementChangeModeSelector: function () {
            return this.selector() + ' .element-change-method';
        },
        elementModeInputSelector: function(){
            return this.selector() + ' .element-mode';
        },
        factory: function ($jQObj) {
            var idx = parseInt($jQObj.attr('data-index'));
            var setId = parseInt($jQObj.attr('data-setid'));
            var mode = $jQObj.attr('data-mode');
            switch (mode) {
                case 'banner':
                    return new htmlBanner(setId, idx);
                case 'post_category':
                    return new htmlPostCategory(setId, idx);
                case 'post_type':
                    return new htmlPostType(setId, idx);
                default :
                    return new htmlCode(setId, idx, mode);
            }
        },
        getTheContent: function () {
            return '';
        },
        getBaseInputName: function(){
            return 'rz[a][a][0][' + this.setIdx + '][elements][' + this.idx + ']';
        },
        getContentInputName: function(){
            return this.getBaseInputName() + '[content]';
        },
        getPinedInputName: function(){
            return this.getBaseInputName() + '[pined]';
        },
        getDisabledInputName: function(){
            return this.getBaseInputName() + '[disabled]';
        },
        getModeInputName: function(){
            return this.getBaseInputName() + '[mode]';
        },
        append: function (mode) {
            var $currElement = $(this.selector());
            $randomizer.$ajax.getElementMarkup(this.setIdx, this.idx + 1, mode, this.getTheContent(), false, false, function (response) {
                var json = response.responseJSON;
                if (json.data['html'] != undefined) {
                    var $newElem = $(json.data['html']);
                    $currElement.after($newElem[0].outerHTML);
                    var $el = html.prototype.factory($newElem);
                    $el.updateIndexes();
                }
            });
        },
        getNextElement: function () {
            var $next = $(this.selector()).next('.form-group');
            if ($next.length) {
                return this.factory($next);
            }
            return false;
        },
        updateInputNames: function($jQObj){
            $(this.pinedSelector()).attr('name', this.getPinedInputName());
            $(this.disableSelector()).attr('name', this.getDisabledInputName());
            $(this.elementModeInputSelector()).attr('name', this.getModeInputName());
        },
        updateIndexes: function () {
            var that = this;
            $(this.selector()).parent().find('.form-group.element').each(function(i){
                var $el = html.prototype.factory($(this));
                $el.unBindClickEvents();
                $el.idx = i;
                $(this).attr('data-index', i);
                $(this).attr('id', 'element-row-random-set-'+$el.setIdx+'-'+i);
                $el.updateInputNames($(this));
            });
            $(this.selector()).parent().find('.form-group.element').each(function(i){
                var $el = html.prototype.factory($(this));
                $el.bindClickEvent();
            });
        },
        remove: function () {
            var $el = this;
            $(this.selector()).slideUp('fast', function () {
                $(this).remove();
                $el.updateIndexes();
            });
        },
        pin: function () {
            if (!this.pined) {
                this.pined = true;
                var $pined = $(this.pinedSelector());
                $pined.addClass('active');
                $pined.val(1);
                $pined.children('i').removeClass('fa-unlock');
                $pined.children('i').addClass('fa-lock');
            }
        },
        unPin: function () {
            if (this.pined) {
                this.pined = false;
                var $pined = $(this.pinedSelector());
                $pined.removeClass('active').blur();
                $pined.val(0);
                $pined.children('i').removeClass('fa-lock');
                $pined.children('i').addClass('fa-unlock');
            }
        },
        disable: function () {
            if (!this.disabled) {
                this.disabled = true;
                var $dis = $(this.disableSelector());
                $dis.addClass('active').blur();
                $dis.val(1);
            }
        },
        activate: function () {
            if (this.disabled) {
                this.disabled = false;
                var $dis = $(this.disableSelector());
                $dis.removeClass('active').blur();
                $dis.val(0);
            }
        },
        changeMode: function (newMode) {
        },
        unBindClickEvents: function(){
            $(this.elementAddSelector()).next().find('li').unbind('click');
            $(this.elementAddSelector()).next().find('li').find('a').unbind('click');
            $(this.elementDeleteSelector()).unbind('click');
            $(this.pinedSelector()).unbind('click');
            $(this.disableSelector()).unbind('click');
        },
        bindClickEvent: function () {
            var $html = this;
            $(this.elementAddSelector()).next().find('li').unbind('click').click(function (e) {
                e.preventDefault();
                var mode = $(this).attr('data-mode');
                $html.append(mode);
            });

            $(this.elementAddSelector()).next().find('li').find('a').unbind('click').click(function (e) {
                e.preventDefault();
            });

            $(this.elementDeleteSelector()).unbind('click').click(function () {
                var r = confirm("Are you sure you want to delete this element?");
                if (r != true) {
                    return r;
                }
                $html.remove();
            });

            $(this.pinedSelector()).unbind('click').click(function () {
                if ($(this).hasClass('active')) {
                    $html.unPin();
                } else {
                    $html.pin();
                }
            });

            $(this.disableSelector()).unbind('click').click(function () {
                if ($(this).hasClass('active')) {
                    $html.activate();
                } else {
                    $html.disable();
                }
            });
        }
    };

    var htmlModesList = function (setIdx, idx) {
        html.apply(this, arguments);
        this.markUp = '<ul data-index="{{idx}}"'
        + 'data-set="{{slug}}"'
        + 'data-setid="{{setIdx}}"'
        + 'class="dropdown-menu">'
        + '<li data-mode="html"><a href="#">HTML</a></li>'
        + '<li data-mode="php"><a href="#">PHP</a></li>'
        + '<li data-mode="markdown"><a href="#">Markdown</a></li>'
        + '<li data-mode="javascript"><a href="#">Javascript</a></li>'
        + '<li data-mode="text"><a href="#">Text</a></li>'
        + '<li data-mode="banner"><a href="#">Banner</a></li>'
        + '</ul>';
    };

    var htmlCode = function (setIdx, idx, mode) {
        html.apply(this, arguments);
        this.mode = (mode == undefined || mode == null || html.prototype.codeModes[mode] == undefined) ? 'html' : mode;
        this.bindEditor();
    };

    htmlCode.prototype = Object.create(html.prototype);
    htmlCode.prototype.constructor = htmlCode;

    htmlCode.prototype.textAreaSelector = function () {
        return this.selector() + ' .element-text-area';
    };

    htmlCode.prototype.updateIndexes = function(){
        html.prototype.updateIndexes.call(this);
        this.bindEditor();
    };


    htmlCode.prototype.updateInputNames = function(){
        html.prototype.updateInputNames.call(this);
        $(this.textAreaSelector()).attr('name', this.getContentInputName());
    };

    htmlCode.prototype.init = function(){
        html.prototype.init.call(this);
    };

    htmlCode.prototype.bindEditor = function () {
        $(this.selector()).find('.editor-area').remove();
        var textarea = $(this.textAreaSelector());
        var height = parseInt(textarea.attr('rows')) * 25;
        var editDiv = $('<div>', {
            position: 'absolute',
            width: '100%',
            height: height + 'px',
            'class': textarea.attr('class') + ' editor-area'
        }).insertBefore(textarea);

        textarea.css('display', 'none');

        var editor = ace.edit(editDiv[0]);
        editor.renderer.setShowGutter(false);
        editor.getSession().setValue(textarea.val());
        editor.getSession().setMode("ace/mode/" + this.mode);

        editor.setOptions({
            enableBasicAutocompletion: true,
            enableSnippets: true,
            enableLiveAutocompletion: false,
            autoScrollEditorIntoView: true,
            animatedScroll: true,
            showInvisibles: false,
            fadeFoldWidgets: true,
            showFoldWidgets: true,
            showLineNumbers: true,
            showGutter: true,
            displayIndentGuides: true,
            fontSize: '14px',
            fontFamily: 'mono',
            highlightActiveLine: false
        });
        editor.setTheme("ace/theme/github");

        editor.getSession().on('change', function () {
            textarea.val(editor.getSession().getValue());
        });

        var $el = this;
        $(this.elementChangeModeSelector()).next().find('li').unbind('click').click(function (e) {
            e.preventDefault();
            var mode = $(this).attr('data-mode');
            $el.mode = mode;
            textarea.attr('data-editor', mode);
            $($el.selector()).find('.text-area-wrapper').attr('data-method', mode);
            $($el.elementModeInputSelector()).val(mode);
            editor.getSession().setMode("ace/mode/" + mode);
        });

        editor.focus();
    };

    var htmlBanner = function (setIdx, idx) {
        html.apply(this, arguments);
        this.mode = 'banner';
    };

    htmlBanner.prototype = Object.create(html.prototype);
    htmlBanner.prototype.constructor = htmlBanner;

    htmlBanner.prototype.imageInputSelector = function () {
        return this.selector() + ' #elements-' + this.setIdx + '-' + this.idx + '-image';
    };
    htmlBanner.prototype.linkInputSelector = function () {
        return this.selector() + ' #elements-' + this.setIdx + '-' + this.idx + '-link';
    };
    htmlBanner.prototype.targetInputSelector = function () {
        return this.selector() + ' #elements-' + this.setIdx + '-' + this.idx + '-target';
    };

    htmlBanner.prototype.updateInputNames = function($obj){
        html.prototype.updateInputNames.call(this);
        var conInputName = this.getContentInputName();
        $obj.find('.input-image').attr('name', conInputName+'[image]');
        $obj.find('.input-link').attr('name', conInputName+'[link]');
        $obj.find('.input-target').attr('name', conInputName+'[target]');
        $obj.find('.input-image').attr('id', 'elements-'+this.setIdx+'-'+this.idx+'-image');
        $obj.find('.input-link').attr('id', 'elements-'+this.setIdx+'-'+this.idx+'-link');
        $obj.find('.input-target').attr('id', 'elements-'+this.setIdx+'-'+this.idx+'-target');
    };

    htmlBanner.prototype.unBindClickEvents = function(){
        html.prototype.unBindClickEvents.call(this);
        $(this.selector()).find('.input-media-btn').unbind('click');
    };

    htmlBanner.prototype.bindClickEvent = function(init){
        init = init == undefined ? false : init;
        html.prototype.bindClickEvent.call(this);
        if(!init)
            this.bindMediaGallery();
    };

    htmlBanner.prototype.bindMediaGallery = function(){
        $(this.selector())
            .find('.input-media-wrapper')
            .each(function(){
                var $wrapper = $(this);
                var $input = $wrapper.find('.media-input');
                $wrapper
                    .find('.input-media-btn')
                    .unbind('click')
                    .click(function () {
                        $menu_pages.openFileFrame($input);
                    }
                )
            });
    };

    var htmlPostCategory = function (setIdx, idx) {
        html.apply(this, arguments);
        this.mode = 'post_category';
    };

    htmlPostCategory.prototype = Object.create(html.prototype);
    htmlPostCategory.prototype.constructor = htmlPostCategory;

    var htmlPostType = function (setIdx, idx) {
        html.apply(this, arguments);
        this.mode = 'post_type';
    };

    htmlPostType.prototype = Object.create(html.prototype);
    htmlPostType.prototype.constructor = htmlPostType;

    $('.form-group.element').each(function (i) {
        var $el = html.prototype.factory($(this));
        $el.bindClickEvent(true);
    });

    setManager.prototype.bindDeleteEvent();
})(jQuery);