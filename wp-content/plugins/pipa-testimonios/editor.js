( function( blocks, element, blockEditor, components, i18n, serverSideRender ) {
	var el = element.createElement;
	var registerBlockType = blocks.registerBlockType;
	var useBlockProps = blockEditor.useBlockProps;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody = components.PanelBody;
	var TextControl = components.TextControl;
	var __ = i18n.__;
	var ServerSideRender = serverSideRender;

	registerBlockType( 'pipa/testimonios-carousel', {
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
						{ title: __( 'Ajustes', 'pipa-testimonios' ) },
						el( TextControl, {
							label: __( 'Título de la sección', 'pipa-testimonios' ),
							value: attributes.titulo,
							onChange: function( value ) {
								setAttributes( { titulo: value } );
							},
						} )
					)
				),
				el( ServerSideRender, {
					block: 'pipa/testimonios-carousel',
					attributes: attributes,
				} )
			);
		},
		save: function() {
			return null;
		},
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n, window.wp.serverSideRender );
