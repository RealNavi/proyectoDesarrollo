( function( blocks, element, blockEditor, components, i18n, serverSideRender ) {
	var el = element.createElement;
	var registerBlockType = blocks.registerBlockType;
	var useBlockProps = blockEditor.useBlockProps;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody = components.PanelBody;
	var TextControl = components.TextControl;
	var __ = i18n.__;
	var ServerSideRender = serverSideRender;

	registerBlockType( 'pipa/productos-destacados', {
		edit: function( props ) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps();

			return el(
				'div',
				blockProps,
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __( 'Ajustes', 'pipa-productos-destacados' ) },
						el( TextControl, {
							label: __( 'Cantidad a mostrar (0 = todos)', 'pipa-productos-destacados' ),
							type: 'number',
							value: attributes.cantidad,
							onChange: function( value ) {
								setAttributes( { cantidad: parseInt( value, 10 ) || 0 } );
							},
						} )
					)
				),
				el( ServerSideRender, {
					block: 'pipa/productos-destacados',
					attributes: attributes,
				} )
			);
		},
		save: function() {
			return null;
		},
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n, window.wp.serverSideRender );
