import { FormControl, FormLabel, Input } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useEffect, useState } from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	defaultValue?: string;
}

const Description: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const { register, setValue } = useFormContext();

	return (
		<FormControl>
			<FormLabel>{__('Course Description', 'masteriyo')}</FormLabel>
			<Input type="hidden" {...register('description')} value={defaultValue} />
			{/* <Editor
				editorState={editorState}
				onEditorStateChange={(editorState) => setEditorState(editorState)}
				readOnly
			/> */}
		</FormControl>
	);
};

export default Description;
