(function () {
    if (wp.blocks.getBlockType('smn/popover')) {
        return;
    }

    var useBlockProps = wp.blockEditor.useBlockProps;
    var InnerBlocks = wp.blockEditor.InnerBlocks;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var PanelBody = wp.components.PanelBody;
    var TextControl = wp.components.TextControl;

    var ALLOWED_BLOCKS = [
        'core/paragraph',
        'core/heading',
        'core/image',
        'core/buttons',
        'core/button',
        'core/list'
    ];

    wp.blocks.registerBlockType('smn/popover', {
        apiVersion: 2,
        title: 'Popover',
        icon: 'welcome-widgets-menus',
        category: 'widgets',
        attributes: {
            popoverId: {
                type: 'string',
                default: ''
            }
        },
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var popoverId = attributes.popoverId;
            var blockProps = useBlockProps({ className: 'wp-block-smn-popover' });

            return wp.element.createElement(
                wp.element.Fragment,
                null,
                wp.element.createElement(
                    InspectorControls,
                    null,
                    wp.element.createElement(
                        PanelBody,
                        { title: 'Ajustes del popover', initialOpen: true },
                        wp.element.createElement(TextControl, {
                            label: 'ID del popover',
                            value: popoverId,
                            onChange: function (value) {
                                setAttributes({ popoverId: value });
                            },
                            help: 'Ejemplo: aeronautico-defensa (minúsculas, sin espacios, guiones para separar palabras). Este ID debe coincidir con el atributo popovertarget del botón que abrirá este popover.'
                        })
                    )
                ),
                wp.element.createElement(
                    'div',
                    blockProps,
                    wp.element.createElement(
                        'p',
                        { className: 'smn-popover-editor-help' },
                        'Contenedor con atributo popover. Define el ID desde el panel lateral.'
                    ),
                    wp.element.createElement(InnerBlocks, {
                        allowedBlocks: ALLOWED_BLOCKS
                    })
                )
            );
        },
        save: function () {
            return wp.element.createElement(InnerBlocks.Content, null);
        }
    });
})();