import { FormControl, FormLabel, Input } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';

interface Props {
	defaultValue?: string;
}

const DateCreated: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	return (
		<FormControl py="3">
			<FormLabel>{__('Date created', 'masteriyo')}</FormLabel>
			<Input defaultValue={defaultValue} disabled />
		</FormControl>
	);
};

export default DateCreated;
