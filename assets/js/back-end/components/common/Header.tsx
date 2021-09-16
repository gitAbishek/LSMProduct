import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Flex,
	Heading,
	Image,
	Link,
	List,
	ListIcon,
	ListItem,
	Stack,
	useBreakpointValue,
	useMediaQuery,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { ReactElement } from 'react';
import { BiBook, BiCog, BiEdit } from 'react-icons/bi';
import { NavLink, useLocation, useParams } from 'react-router-dom';
import { navActiveStyles, navLinkStyles } from '../../config/styles';
import { Logo } from '../../constants/images';
import routes from '../../constants/routes';

interface Props {
	firstBtn?: {
		label: string;
		action: () => void;
		isDisabled?: boolean;
		isLoading?: boolean;
	};
	secondBtn?: {
		label: string;
		action: () => void;
		isDisabled?: boolean;
		isLoading?: boolean;
		icon?: ReactElement;
	};
	thirdBtn?: {
		label: string;
		action: () => void;
		isDisabled?: boolean;
		isLoading?: boolean;
		icon?: ReactElement;
	};
	course?: {
		name: string;
		id: number;
		previewUrl: string;
	};
	showLinks?: boolean;
	showPreview?: boolean;
	showCourseName?: boolean;
}

const Header: React.FC<Props> = (props) => {
	const {
		firstBtn,
		secondBtn,
		thirdBtn,
		course,
		children,
		showLinks,
		showPreview,
		showCourseName,
	} = props;

	const location = useLocation();
	const { courseId }: any = useParams();
	const buttonSize = useBreakpointValue(['sm', 'md']);
	const [isDesktop] = useMediaQuery('(min-width: 48em)');
	return (
		<Box bg="white" w="full" shadow="header">
			<Container maxW="container.xl" bg="white">
				<Flex direction="row" justifyContent="space-between" align="center">
					<Stack
						direction="row"
						spacing={['3', null, '8']}
						align="center"
						minHeight="16">
						<NavLink to={routes.courses.list}>
							<Image src={Logo} w="36px" d={['none', 'block']} />
						</NavLink>
						{showCourseName && (
							<>
								{course && (
									<Heading fontSize="md" fontWeight="medium">
										{course.name}
									</Heading>
								)}
							</>
						)}
						{children}
						{showLinks && courseId && (
							<List d="flex">
								<ListItem mb="0">
									<Link
										as={NavLink}
										sx={navLinkStyles}
										_activeLink={navActiveStyles}
										to={routes.courses.edit.replace(
											':courseId',
											courseId.toString()
										)}>
										<ListIcon as={BiBook} />
										{__('Course', 'masteriyo')}
									</Link>
								</ListItem>

								<ListItem mb="0">
									<Link
										as={NavLink}
										sx={navLinkStyles}
										isActive={() => location.pathname.includes('/courses')}
										_activeLink={navActiveStyles}
										to={
											routes.courses.edit.replace(
												':courseId',
												courseId.toString()
											) + '?page=builder'
										}>
										<ListIcon as={BiEdit} />
										{__('Builder', 'masteriyo')}
									</Link>
								</ListItem>

								<ListItem mb="0">
									<Link
										as={NavLink}
										sx={navLinkStyles}
										_activeLink={navActiveStyles}
										to={
											routes.courses.edit.replace(
												':courseId',
												courseId.toString()
											) + '?page=settings'
										}>
										<ListIcon as={BiCog} />
										{__('Settings', 'masteriyo')}
									</Link>
								</ListItem>
							</List>
						)}
					</Stack>

					<ButtonGroup>
						{firstBtn && (
							<Button
								size={buttonSize}
								variant="outline"
								onClick={firstBtn.action}
								isLoading={firstBtn.isLoading}
								isDisabled={firstBtn.isDisabled}>
								{firstBtn.label}
							</Button>
						)}

						{showPreview && course?.previewUrl && (
							<Link href={course?.previewUrl} isExternal>
								<Button size={buttonSize} variant="outline">
									{__('Preview', 'masteriyo')}
								</Button>
							</Link>
						)}
						{secondBtn && (
							<Button
								size={buttonSize}
								variant="outline"
								colorScheme="blue"
								onClick={secondBtn.action}
								leftIcon={secondBtn.icon}
								isDisabled={secondBtn.isDisabled}
								isLoading={secondBtn.isLoading}>
								{secondBtn.label}
							</Button>
						)}

						{thirdBtn && (
							<Button
								size={buttonSize}
								colorScheme="blue"
								onClick={thirdBtn.action}
								isDisabled={thirdBtn.isDisabled}
								leftIcon={isDesktop ? thirdBtn.icon : <></>}
								isLoading={thirdBtn.isLoading}>
								{thirdBtn.label}
							</Button>
						)}
					</ButtonGroup>
				</Flex>
			</Container>
		</Box>
	);
};

export default Header;
