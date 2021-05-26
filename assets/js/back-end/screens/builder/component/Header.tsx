import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Flex,
	Icon,
	Image,
	Link,
	Stack,
	Tab,
	TabList,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiBook, BiCog, BiEdit } from 'react-icons/bi';
import { Link as RouterLink } from 'react-router-dom';

import { Logo } from '../../../constants/images';
import routes from '../../../constants/routes';

interface Props {
	previewUrl: string | any;
	onSave: () => void;
}

const Header: React.FC<Props> = (props) => {
	const { previewUrl, onSave } = props;
	const tabStyles = {
		fontWeight: 'medium',
		fontSize: 'sm',
		py: '6',
		px: 0,
		mx: 4,
	};

	const iconStyles = {
		mr: '2',
	};

	return (
		
	);
};

export default Header;
