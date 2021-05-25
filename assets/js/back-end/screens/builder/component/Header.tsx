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
		<Box bg="white" w="full">
			<Container maxW="container.xl">
				<Flex direction="row" justifyContent="space-between" align="center">
					<Stack direction="row" spacing="12" align="center">
						<Box>
							<RouterLink to={routes.courses.list}>
								<Image src={Logo} alt="Masteriyo Logo" w="120px" />
							</RouterLink>
						</Box>
						<TabList borderBottom="none" bg="white">
							<Tab sx={tabStyles}>
								<Icon as={BiBook} sx={iconStyles} />
								{__('Course', 'masteriyo')}
							</Tab>
							<Tab sx={tabStyles}>
								<Icon as={BiEdit} sx={iconStyles} />
								{__('Builder', 'masteriyo')}
							</Tab>
							<Tab sx={tabStyles}>
								<Icon as={BiCog} sx={iconStyles} />
								{__('Settings', 'masteriyo')}
							</Tab>
						</TabList>
					</Stack>
					<ButtonGroup>
						<Link href={previewUrl} isExternal>
							<Button variant="outline">Preview</Button>
						</Link>

						<Button colorScheme="blue" type="submit">
							{__('Save', 'masteriyo')}
						</Button>
					</ButtonGroup>
				</Flex>
			</Container>
		</Box>
	);
};

export default Header;
