import { Button, ButtonProps, Icon } from '@chakra-ui/react';
import React from 'react';
import { BiPlus } from 'react-icons/bi';

interface Props extends ButtonProps {}

const AddNewButton: React.FC<Props> = (props) => {
	return (
		<Button
			variant="link"
			sx={{
				span: {
					fontSize: 'lg',
					rounded: 'full',
					shadow: 'xs',
					bg: 'blue.500',
					color: 'white',
					h: '26px',
					w: '26px',
					d: 'flex',
					alignItems: 'center',
					justifyContent: 'center',
				},
			}}
			leftIcon={<Icon as={BiPlus} />}
			{...props}>
			{props.children}
		</Button>
	);
};

export default AddNewButton;
