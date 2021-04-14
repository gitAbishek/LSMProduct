import { FormControl, FormLabel, Input } from '@chakra-ui/react';
import { Editor } from '@tinymce/tinymce-react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Controller, useFormContext } from 'react-hook-form';

interface Props {
	defaultValue?: string;
}

const Description: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const { control } = useFormContext();

	return (
		<FormControl>
			<FormLabel>{__('Course Description', 'masteriyo')}</FormLabel>
			<Controller
				name="description"
				control={control}
				render={({ field: { onChange } }) => (
					<Editor
						initialValue={defaultValue}
						tinymceScriptSrc="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.1/tinymce.min.js"
						onEditorChange={onChange}
					/>
				)}
			/>
		</FormControl>
	);
};

export default Description;
