import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Flex,
	Image,
	Link,
	List,
	ListIcon,
	ListItem,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiBook, BiCog } from 'react-icons/bi';
import { Link as RouterLink, NavLink } from 'react-router-dom';
import { Logo } from '../../constants/images';
import routes from '../../constants/routes';

interface Props {
	hideAddNewCourseBtn?: boolean;
	hideCoursesMenu?: boolean;
}

const Header: React.FC<Props> = (props) => {
	const { hideAddNewCourseBtn, hideCoursesMenu } = props;

	const navLinkStyles = {
		mr: '10',
		py: '6',
		d: 'flex',
		alignItems: 'center',
		fontWeight: 'medium',
		fontSize: 'sm',
	};

	const navActiveStyles = {
		borderBottom: '2px',
		borderColor: 'blue.500',
		color: 'blue.500',
	};

	return (
		<Box bg="white" w="full">
			<Container maxW="container.xl" bg="white">
				<Flex direction="row" justifyContent="space-between" align="center">
					<Stack direction="row" spacing="12" align="center" minHeight="16">
						<Box>
							<RouterLink to={routes.courses.list}>
								<Image src={Logo} alt="Masteriyo Logo" w="120px" />
							</RouterLink>
						</Box>
						{!hideCoursesMenu && (
							<List d="flex">
								<ListItem mb="0">
									<Link
										as={NavLink}
										sx={navLinkStyles}
										_activeLink={navActiveStyles}
										_hover={{ color: 'blue.500' }}
										to={routes.courses.list}>
										<ListIcon as={BiBook} />
										Courses
									</Link>
								</ListItem>

								<ListItem mb="0">
									<Link
										as={NavLink}
										sx={navLinkStyles}
										_activeLink={navActiveStyles}
										_hover={{ color: 'blue.500' }}
										to={routes.settings}>
										<ListIcon as={BiCog} />
										Settings
									</Link>
								</ListItem>
							</List>
						)}
					</Stack>

					{props.children}

					{!hideAddNewCourseBtn && (
						<ButtonGroup>
							<RouterLink to={routes.courses.add}>
								<Button colorScheme="blue">
									{__('Add New Course', 'masteriyo')}
								</Button>
							</RouterLink>
						</ButtonGroup>
					)}
				</Flex>
			</Container>
		</Box>
	);
};

export default Header;
