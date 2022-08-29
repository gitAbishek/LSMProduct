import { Box, Textarea } from '@chakra-ui/react';
import React, { PropsWithChildren } from 'react';
import { isUndefined } from '../../utils/utils';

const { wp, addEventListener, removeEventListener } = window as any;
class WPEditor extends React.Component<PropsWithChildren<any>, any> {
	constructor(props: PropsWithChildren<any>) {
		super(props);
		this.state = {
			editor: null,
			id: props.id,
		};

		this.initEditor = this.initEditor.bind(this);
	}

	componentDidMount() {
		if (document.readyState === 'complete') {
			this.initEditor();
		} else {
			addEventListener('DOMContentLoaded', this.initEditor);
		}
	}

	componentWillUnmount() {
		removeEventListener('DOMContentLoaded', this.initEditor);
		wp.editor.remove(this.state.id);
	}

	initEditor(id = null) {
		const $this = this;
		id = null !== this.state.id ? this.state.id : $this.props.id;

		if (!isUndefined(wp) && !isUndefined(wp.editor)) {
			wp.editor.initialize(`${id}`, {
				tinymce: {
					wpautop: true,
					plugins:
						'charmap textcolor colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview',
					toolbar1:
						'formatselect bold italic underline bullist numlist blockquote alignleft aligncenter alignright link wp_more media wp_add_media wp_adv',
					toolbar2:
						'forecolor strikethrough wp_code wp_page removeformat charmap outdent indent undo redo wp_help ',
					setup: function (editor: any) {
						$this.setState({
							editor,
							id: $this.props.id,
						});
						editor.on('keyup change', function (e: any) {
							const content = editor.getContent();
							$this.props.onContentChange(content, $this.props.name);
						});
					},
					height: $this.props.height || 350,
				},
			});
		}
	}

	render() {
		const name = this.props.name ? this.props.name : '';
		return (
			<Box
				sx={{
					'.mce-tinymce': {
						shadow: 'input',
						border: '1px',
						borderColor: 'gray.100',

						'.mce-statusbar': {
							borderTop: '1px solid',
							borderColor: 'gray.100',
						},

						'.mce-container-body': {
							bg: 'white',
						},

						'.mce-toolbar': {
							'.mce-btn': {
								borderColor: 'transparent',
								shadow: 'none',

								'&.mce-listbox': {
									borderColor: 'gray.100',
									rounded: '3px',
								},

								button: {
									rounded: '3px',
									padding: '1',
									transition: 'all 0.35s',

									'&:hover': {
										borderColor: 'gray.100',
									},
								},

								'.mce-ico': {
									fontSize: '1rem',
									lineHeight: 1.2,
								},

								'&.mce-active': {
									bg: 'primary.500',
									color: 'white',
								},
							},
						},

						'.mce-toolbar-grp.mce-container.mce-panel.mce-first.mce-last': {
							borderBottom: '2px solid',
							borderColor: 'gray.100',
						},
						'.mce-top-part::before': {
							boxShadow: 'none',
						},
					},
				}}>
				<Textarea
					id={this.props.id}
					value={this.props.value}
					onChange={(e: any) => this.props.onContentChange(e.target.value)}
					name={name}
				/>
			</Box>
		);
	}
}

export default WPEditor;
