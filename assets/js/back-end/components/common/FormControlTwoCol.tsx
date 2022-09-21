import { FormControl, FormControlProps } from '@chakra-ui/react';
import React from 'react';
import { labelStyles } from '../../config/styles';

const FormControlTwoCol: React.FC<FormControlProps> = (props) => {
	const { children } = props;
	return (
		<FormControl
			{...props}
			display="flex"
			flexDirection={['column', 'column', 'column', 'row']}
			sx={{
				'.chakra-form__label': labelStyles,
				'> *': {
					flex: 1,
				},
			}}>
			{children}
		</FormControl>
	);
};

export default FormControlTwoCol;
