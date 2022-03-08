import { Textarea } from '@chakra-ui/react';
import React, { PropsWithChildren } from 'react';
import { WPLocalized } from '../../utils/global';
import { isUndefined } from '../../utils/utils';

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
		this.initEditor();
	}

	componentWillUnmount() {
		(window as any).tinymce.execCommand(
			'mceRemoveControl',
			true,
			`#${this.state.id}`
		);
		WPLocalized.editor.remove(this.state.id);
		(window as any).tinymce.remove(this.state.editor);
	}

	initEditor(id = null) {
		const $this = this;
		id = null !== this.state.id ? this.state.id : $this.props.id;

		if (!isUndefined(WPLocalized) && !isUndefined(WPLocalized.editor)) {
			WPLocalized.editor.initialize(`${id}`, {
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
			<Textarea
				id={this.props.id}
				value={this.props.value}
				onChange={(e: any) => this.props.onContentChange(e.target.value)}
				name={name}
			/>
		);
	}
}

export default WPEditor;
