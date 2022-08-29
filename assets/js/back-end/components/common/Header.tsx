import {
	Box,
	Button,
	ButtonGroup,
	Container,
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
import ReviewNotice from './ReviewNotice';

interface Course {
	name: string | React.ReactNode;
	id: number;
	previewUrl: string;
}

type HeaderDirection = 'row' | 'column';
type HeaderAlign = 'center' | 'left' | 'right';

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
	course?: Course;
	showLinks?: boolean;
	showPreview?: boolean;
	showCourseName?: boolean;
	direction?: HeaderDirection[];
	align?: HeaderAlign[];
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
		direction = ['column', 'column', 'row', 'row'],
		align = ['left', 'left', 'center', 'center'],
	} = props;

	const location = useLocation();
	const { courseId }: any = useParams();
	const buttonSize = useBreakpointValue(['sm', 'md']);
	const [isDesktop] = useMediaQuery('(min-width: 48em)');
	return (
		<>
			<Box bg="white" w="full" shadow="header" pb={['3', '3', 0]}>
				<Container maxW="container.xl">
					<Stack
						direction={direction}
						justifyContent="space-between"
						align={align}>
						<Stack
							direction={['column', 'column', 'row', 'row']}
							spacing={['3', null, '8']}
							align={['left', 'left', 'center', 'center']}
							minHeight="16">
							<Stack
								direction={['row', 'row', 'column', 'row']}
								alignItems="center"
								textAlign="center"
								spacing="4">
								<NavLink to={routes.courses.list}>
									<Image src={Logo} w="36px" minW="36px" />
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
							</Stack>

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
									onClick={thirdBtn.action}
									isDisabled={thirdBtn.isDisabled}
									leftIcon={isDesktop ? thirdBtn.icon : <></>}
									isLoading={thirdBtn.isLoading}>
									{thirdBtn.label}
								</Button>
							)}
						</ButtonGroup>
					</Stack>
				</Container>
			</Box>
			<ReviewNotice />
		</>
	);
};

export default Header;
