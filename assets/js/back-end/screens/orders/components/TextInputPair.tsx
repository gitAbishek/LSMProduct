import {
	FormControl,
	FormErrorMessage,
	FormLabel,
	Input,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	defaultValue1?: string;
	defaultValue2?: string;
	label1?: string;
	label2?: string;
	name1?: string;
	name2?: string;
}
const TextInputPair: React.FC<Props> = (props) => {
	const { defaultValue1, defaultValue2, label1, label2, name1, name2 } = props;
	const {
		register,
		formState: { errors },
	} = useFormContext();

	return (
		<Stack direction="row" spacing="8" py="3">
			<FormControl isInvalid={errors && name1 && errors[name1]}>
				<FormLabel>{label1}</FormLabel>
				<Input
					defaultValue={defaultValue1}
					{...(name1 ? register(`${name1}` as const) : {})}
				/>
				<FormErrorMessage>
					{errors && name1 && errors[name1] && errors[name1].message}
				</FormErrorMessage>
			</FormControl>
			<FormControl isInvalid={errors && name2 && errors[name2]}>
				<FormLabel>{label2}</FormLabel>
				<Input
					defaultValue={defaultValue2}
					{...(name2 ? register(`${name2}` as const) : {})}
				/>
				<FormErrorMessage>
					{errors && name2 && errors[name2] && errors[name2].message}
				</FormErrorMessage>
			</FormControl>
		</Stack>
	);
};

export default TextInputPair;
