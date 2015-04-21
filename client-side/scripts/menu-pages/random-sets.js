/*!
 * Random sets pages script
 *
 * Copyright: © 2014
 * {@link -*-}
 *
 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @package XRandomizer
 * @since 140914
 */

(function ($) {
    /*******************************************************
     *
     * RandomElement Class
     *
     ******************************************************/
    /**
     * Random element manager
     * @param elementIndex
     * @param elementSet
     * @param elementSetId
     * @constructor
     * @param mode
     */
    function RandomElement(elementIndex, elementSet, elementSetId, mode) {
        this.elementSet = elementSet.toString();
        this.elementSetId = elementSetId.toString();
        this.mode = mode == undefined ? 'html' : mode.toString();

        this.oldElement = {};
        this.oldElement.elementIndex = parseInt(elementIndex);

        this.oldElement.$wrapper = $('#element-row-' + elementSet + '-' + this.oldElement.elementIndex.toString());
        this.oldElement.$wrapper.textAreaWrapper = this.oldElement.$wrapper.children('.text-area-wrapper');
        this.oldElement.$wrapper.textArea = this.oldElement.$wrapper.textAreaWrapper.children('.text-area');
        this.oldElement.$wrapper.elementControls = this.oldElement.$wrapper.children('.element-control');

        this.newElement = {
            elementIndex: parseInt(elementIndex) + 1,
            $wrapper: null
        };
    }

    RandomElement.prototype.addNewElementAfterOld = function () {
        this.formNewElement('');
        this.updateNextElements();
        this.addAfterOld();
        this.bindClickEvent();
        SetManager.prototype.bindEditor($(this.newElement.$wrapper.textArea));
    };

    RandomElement.prototype.formNewElement = function (textAreaContent) {
        this.newElement.$wrapper = $(this.oldElement.$wrapper[0].outerHTML);
        this.newElement.$wrapper.find('.ace_editor').remove();
        this.newElement.$wrapper.textAreaWrapper = this.newElement.$wrapper.children('.text-area-wrapper');
        this.newElement.$wrapper.textArea = this.newElement.$wrapper.textAreaWrapper.children('.text-area');
        this.newElement.$wrapper.elementControls = this.newElement.$wrapper.children('.element-control');

        this.newElement.$wrapper.attr('data-index', this.newElement.elementIndex);
        this.newElement.$wrapper.attr('id', 'element-row-' + this.elementSet + '-' + this.newElement.elementIndex);

        this.newElement.$wrapper.textAreaWrapper.attr('data-method', this.mode);

        this.newElement.$wrapper.textArea.attr('id', 'elements-' + this.elementSetId + '-' + this.newElement.elementIndex);
        this.newElement.$wrapper.textArea.attr('name', 'rz[a][a][0][' + this.elementSetId + '][elements][' + this.newElement.elementIndex + '][content]');
        this.newElement.$wrapper.textArea.val(textAreaContent);
        this.newElement.$wrapper.textArea.attr('data-editor', this.mode);

        this.newElement.$wrapper.elementControls.remove();
        this.newElement.$wrapper.append(this.getElementActionsMarkUp(
            this.elementSetId,
            this.elementSet,
            this.newElement.elementIndex,
            false,
            false
        ));
    };

    RandomElement.prototype.addAfterOld = function () {
        this.newElement.$wrapper.hide();
        this.oldElement.$wrapper.after(this.newElement.$wrapper);
        this.newElement.$wrapper.slideDown('fast');
        this.newElement.$wrapper.textArea.focus();
    };

    RandomElement.prototype.bindClickEvent = function () {
        $('.element-add').next().find('li').unbind('click').click(function (e) {
            e.preventDefault();
            var index = $(this).parent().attr('data-index');
            var elemSet = $(this).parent().attr('data-set');
            var elemSetId = $(this).parent().attr('data-setid');
            var mode = $(this).attr('data-mode');

            var el = new RandomElement(index, elemSet, elemSetId, mode);
            el.addNewElementAfterOld();
        });

        $('.element-add').next().find('li').find('a').unbind('click').click(function (e) {
            e.preventDefault();
        });

        $('.element-delete').unbind('click').click(function () {
            var index = $(this).attr('data-index');
            var elemSet = $(this).attr('data-set');
            var elemSetId = $(this).attr('data-setid');

            var container = $('#elements-' + elemSetId + '-' + index);
            if ((container != undefined && container.val().length > 0) || container == undefined) {
                var r = confirm("Are you sure you want to delete this element?");
                if (r != true) {
                    return r;
                }
            }

            var el = new RandomElement(index, elemSet, elemSetId);
            el.oldElement.$wrapper.slideUp('fast', function () {
                $(this).remove()
            });
        });

        $('.element-pin').unbind('click').click(function () {
            var setId = $(this).attr('data-setid');
            var index = $(this).attr('data-index');
            if ($(this).hasClass('active')) {
                $(this).removeClass('active').blur();
                $('#pined-' + setId + '-' + index).val(0);
                $(this).children('i').removeClass('fa-lock');
                $(this).children('i').addClass('fa-unlock');
            } else {
                $(this).addClass('active').blur();
                $('#pined-' + setId + '-' + index).val(1);
                $(this).children('i').removeClass('fa-unlock');
                $(this).children('i').addClass('fa-lock');
            }
        });

        $('.element-disable').unbind('click').click(function () {
            var setId = $(this).attr('data-setid');
            var index = $(this).attr('data-index');
            if ($(this).hasClass('active')) {
                $(this).removeClass('active').blur();
                $('#disabled-' + setId + '-' + index).val(0);
            } else {
                $(this).addClass('active').blur();
                $('#disabled-' + setId + '-' + index).val(1);
            }
        });
    };

    RandomElement.prototype.updateNextElements = function () {
        var that = this;

        this.oldElement.$wrapper.nextAll('.form-group').each(function (idx) {

            var prevElemIndex = that.newElement.elementIndex + idx;
            var curElemIndex = prevElemIndex + 1;
            var $wrapper = $(this);

            $wrapper.textAreaWrapper = $wrapper.children('.text-area-wrapper');
            $wrapper.textArea = $wrapper.textAreaWrapper.children('.text-area');
            $wrapper.elementControls = $wrapper.children('.element-control');

            $wrapper.attr('data-index', curElemIndex);
            $wrapper.attr('id', 'element-row-' + that.elementSet + '-' + curElemIndex.toString());

            $wrapper.textArea.attr('id', 'elements-' + that.elementSetId + '-' + curElemIndex.toString());
            $wrapper.textArea.attr('name', 'rz[a][a][0][' + that.elementSetId + '][elements][' + curElemIndex + '][content]');

            var pined = parseInt($(this).find('input:regex(id, .*pined.*)').val()) == 1;
            var disabled = parseInt($(this).find('input:regex(id, .*disabled.*)').val()) == 1;
            var mode = $(this).find('input:regex(id, .*mode.*)').val();

            $wrapper.elementControls.remove();
            $wrapper.append(that.getElementActionsMarkUp(
                    that.elementSetId,
                    that.elementSet,
                    curElemIndex,
                    pined,
                    disabled,
                    mode
                )
            );
        });
    };

    RandomElement.prototype.getElementActionsMarkUp = function (setIdx, slug, index, pined, disabled, mode) {
        pined = pined == undefined ? false : pined;
        disabled = disabled == undefined ? false : disabled;
        mode = mode == undefined ? this.mode : mode;

        var pinedFaClass = pined ? ' fa-lock ' : ' fa-unlock ';
        var pinedActiveClass = pined ? ' active ' : '';

        var disabledActiveClass = disabled ? ' active ' : '';

        var btnCtrlAttr = ' data-setid="' + setIdx + '" data-set="' + slug + '" data-index="' + index + '" ';

        var out = '<div class="col-sm-2 text-center element-control">';
        out += '<div class="row b-margin-sm">';
        out += '<div class="col-sm-6">';
        out += '<button type="button"' + btnCtrlAttr + 'style="font-size: 1em;" class="btn btn-success element-pin' + pinedActiveClass + '" title="Pin element">';
        out += '<i class="fa ' + pinedFaClass + '"></i>'
        + '</button>';
        out += '</div>';
        out += '<div class="col-sm-6 btn-group">';
        out += '<button type="button"' + btnCtrlAttr + 'style="font-size: 1em; float: none;" '
        + 'class="btn btn-success element-add dropdown-toggle" title="Add new element"'
        + 'data-toggle="xd-v141226-dev-dropdown">'
        + '<i class="fa fa-plus"></i>'
        + '</button>'
        + '<ul class="dropdown-menu" ' + btnCtrlAttr + '>';
        for (var k in SetManager.prototype.elementModes) {
            out += '<li data-mode="' + k + '"><a href="#">' + SetManager.prototype.elementModes[k] + '</a></li>';
        }
        out += '</ul>';
        out += '</div>';
        out += '</div>';

        out += '<div class="row">';
        if (index != 0) {
            out += '<div class="col-sm-6">';
            out += '<button type="button"' + btnCtrlAttr + 'style="font-size: 1em;" class="btn btn-warning element-disable' + disabledActiveClass + '" title="Disable element">'
            + '<i class="fa fa-power-off"></i>'
            + '</button>';
            out += '</div>'; // col-sm-6
            out += '<div class="col-sm-6">';
            out += '<button type="button"' + btnCtrlAttr + 'style="font-size: 1em;" class="btn btn-danger element-delete" title="Delete element">'
            + '<i class="fa fa-trash-o"></i>'
            + '</button>';
            out += '</div>';// col-sm-6
        }
        out += '</div>';// row
        out += '<input id="pined-' + setIdx + '-' + index + '" class="pined" type="hidden" data-initial-value="" value="' + (pined ? '1' : '0') + '" name="rz[a][a][0][' + setIdx + '][elements][' + index + '][pined]">'
        + '<input id="disabled-' + setIdx + '-' + index + '" class="pined" type="hidden" data-initial-value="" value="' + (disabled ? '1' : '0') + '" name="rz[a][a][0][' + setIdx + '][elements][' + index + '][disabled]">'
        + '<input id="mode-' + setIdx + '-' + index + '" class="mode" type="hidden" data-initial-value="" value="' + mode + '" name="rz[a][a][0][' + setIdx + '][elements][' + index + '][mode]">';
        out += '</div>'; //element-control

        return out;
    };

    /*******************************************************
     *
     * Set Manager
     *
     ******************************************************/

    /**
     * Set Manager
     * @param setIdx
     * @constructor
     * @param $selector
     */
    function SetManager(setIdx, $selector) {
        this.setIdx = setIdx;
        this.$selector = $selector;
    }

    SetManager.prototype = {
        /**
         * Autoincrement number of sets
         */
        sets: {
            0: true
        },

        bindDeleteEvent: function () {
            $('.set-delete').unbind('click').click(function () {
                var r = confirm("Are you sure you want to delete this set?");
                if (r != true) {
                    return r;
                }
                var setIdx = parseInt($(this).attr('data-setidx'));
                var selector = $('#' + $(this).attr('data-setselector'));
                var newSet = new SetManager(setIdx, selector);
                newSet.deleteSet();
            });
        },

        bindAddEvent: function () {
            $('.set-add').unbind('click').click(function () {
                var setIdx = parseInt($(this).attr('data-setidx'));
                var selector = $('#' + $(this).attr('data-setselector'));
                var newSet = new SetManager(setIdx, selector);
                newSet.addNewSet();
            });
        },

        bindEditor: function (textarea) {
            var mode = textarea.data('editor');
            var height = parseInt(textarea.attr('rows')) * 25;
            var editDiv = $('<div>', {
                position: 'absolute',
                width: '100%',
                height: height + 'px',
                'class': textarea.attr('class')
            }).insertBefore(textarea);

            textarea.css('display', 'none');

            var editor = ace.edit(editDiv[0]);
            editor.renderer.setShowGutter(false);
            editor.getSession().setValue(textarea.val());
            editor.getSession().setMode("ace/mode/" + mode);

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
            var $wrapper = textarea.parent().parent();
            $wrapper.find('.element-change-method').next().find('li').unbind('click').click(function (e) {
                var index = $(this).parent().attr('data-index');
                var elemSet = $(this).parent().attr('data-set');
                var elemSetId = $(this).parent().attr('data-setid');
                e.preventDefault();
                var mode = $(this).attr('data-mode');
                textarea.attr('data-editor', mode);
                $wrapper.find('.text-area-wrapper').attr('data-method', mode);
                $wrapper.find('#mode-'+elemSetId+'-'+index).val(mode);
                editor.getSession().setMode("ace/mode/" + mode);
            });

            editor.focus();
        },

        addNewSet: function () {
            //this.$selector.after(this.$selector.clone());
            // In the end
            SetManager.prototype.bindAddEvent();
            SetManager.prototype.bindDeleteEvent();
        },

        deleteSet: function () {
            // In the end
            this.$selector.remove();
            SetManager.prototype.bindAddEvent();
            SetManager.prototype.bindDeleteEvent();
        },

        getAnIdForNewSet: function () {
            return SetManager.prototype.sets.length + 1;
        },

        setsStashId: function (id) {
            id = parseInt(id);
            SetManager.prototype.sets[id] = true;
        }
    };

    /********************************************
     * Initialize
     *******************************************/

    SetManager.prototype.bindAddEvent(); // Add event no used at the moment
    SetManager.prototype.bindDeleteEvent();

    RandomElement.prototype.bindClickEvent();

    /***********************************************
     * Code Editor Binding
     ***********************************************/
    ace.require("ace/ext/language_tools");
    $('textarea[data-editor]').each(function () {
        SetManager.prototype.bindEditor($(this));
    });

    SetManager.prototype.elementModes = elementModes;

})(jQuery); // End extension closure.