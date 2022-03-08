import { Input } from '@chakra-ui/react';
import React from 'react';
import { useFormContext } from 'react-hook-form';
import WPEditor from './WPEditor';

interface Props {
	id: string;
	name: any;
	defaultValue?: string;
	height?: number;
}

const Editor: React.FC<Props> = (props) => {
	const { id, name, defaultValue, height } = props;
	const { register, setValue } = useFormContext();

	return (
		<>
			<Input type="hidden" {...register(name)} defaultValue={defaultValue} />
			<WPEditor
				id={id}
				value={defaultValue}
				onContentChange={(value: string) => {
					setValue(name, value);
				}}
				height={height}
			/>
		</>
	);
};

export default Editor;
