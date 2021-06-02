import { Editor as TinyMceEditor } from '@tinymce/tinymce-react';
import React from 'react';
import { Controller } from 'react-hook-form';

interface Props {
	name: `${string}`;
	control: any;
	defaultValue?: string;
}
const Editor: React.FC<Props> = (props) => {
	const { name, control, defaultValue } = props;
	return (
		<Controller
			name={name}
			control={control}
			render={({ field: { onChange } }) => (
				<TinyMceEditor
					initialValue={defaultValue}
					tinymceScriptSrc="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.1/tinymce.min.js"
					onEditorChange={onChange}
				/>
			)}
		/>
	);
};

export default Editor;
