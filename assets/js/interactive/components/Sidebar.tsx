import {
	Accordion,
	Box,
	Button,
	ButtonGroup,
	Heading,
	HStack,
	Icon,
	IconButton,
	Link,
	Stack,
	Text,
	useMediaQuery,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import {
	BiAlignLeft,
	BiChevronLeft,
	BiInfoCircle,
	BiMenu,
	BiX,
} from 'react-icons/bi';
import { useParams } from 'react-router-dom';
import { CloseCone } from '../../back-end/constants/images';
import { CourseProgressItemMap } from '../schemas';
import localized from '../utils/global';
import QuestionList from './qa/QuestionList';
import SidebarItem from './SidebarItem';

interface Props {
	items: [CourseProgressItemMap];
	name: string;
	onToggle: () => void;
	isOpen: boolean;
	isHeaderOpen: boolean;
	coursePermalink: string;
	activeIndex: number;
}

const Sidebar: React.FC<Props> = (props) => {
	const {
		items,
		name,
		onToggle,
		isOpen,
		isHeaderOpen,
		coursePermalink,
		activeIndex,
	} = props;
	const { courseId }: any = useParams();

	const [currentTab, setCurrentTab] = useState<number>(1);
	const [largerThan768] = useMediaQuery('(min-width: 768px)');

	const hasAdminBar = document.body.classList.contains('admin-bar');

	const buttonStyles = {
		variant: 'solid',
		shadow: 'none',
		bg: 'gray.50',
		flex: '1',
		border: '1px',
		borderColor: 'gray.100',
		'.chakra-button__icon': {
			fontSize: 'lg',
		},
		_active: {
			borderColor: 'transparent',
			bg: 'white',
			color: 'blue.500',
		},
	};

	const sidebarTop = () => {
		if (isHeaderOpen) {
			if (hasAdminBar) {
				return '98px';
			} else {
				return '66px';
			}
		} else {
			if (hasAdminBar) {
				return '32px';
			} else {
				return '0';
			}
		}
	};

	let sideBarWidth: '300px' | '100%' = '300px';
	let positionRight: '0px' | '-35px' = '-35px';

	if (largerThan768 && isOpen) {
		sideBarWidth = '300px';
		positionRight = '-35px';
	} else {
		if (isOpen) {
			positionRight = '0px';
		} else {
			positionRight = '-35px';
		}
		sideBarWidth = '100%';
	}

	return (
		<Box
			pos="fixed"
			top={sidebarTop()}
			left="0"
			w={sideBarWidth}
			maxW={largerThan768 ? '300px' : '100%'}
			bg="white"
			zIndex="99"
			bottom="0"
			transition="all 0.35s"
			transform={`translateX(${isOpen ? 0 : '-100%'})`}>
			<Box pos="relative" h="full">
				<IconButton
					variant="unstyled"
					color="white"
					d="flex"
					justifyContent="center"
					position="absolute"
					top="9px"
					right={positionRight}
					fontSize="x-large"
					icon={isOpen ? <BiX /> : <BiMenu />}
					bgSize="cover"
					minW="auto"
					w="36px"
					p="0"
					h="60px"
					bgImage={`url(${CloseCone})`}
					onClick={onToggle}
					aria-label="open sidebar"
				/>
				<Stack direction="column" justifyContent="space-between" h="full">
					<Stack direction="column" spacing="0" flex="1" overflowY="hidden">
						<Stack
							direction="column"
							spacing="1"
							px="4"
							bg="blue.500"
							minH="20"
							justify="center"
							color="white">
							<Heading
								fontSize="lg"
								d="flex"
								alignItems="center"
								fontWeight="semibold">
								{name}
							</Heading>
							<Link href={coursePermalink} fontSize="x-small">
								<HStack spacing="1" align="flex-start">
									<Icon as={BiChevronLeft} fontSize="sm" />
									<Text>{__('Back to Course', 'masteriyo')}</Text>
								</HStack>
							</Link>
						</Stack>
						<Box p="0" position="relative" overflowX="hidden" flex="1">
							{currentTab === 1 && (
								<Accordion allowToggle defaultIndex={activeIndex}>
									{items.map((item: CourseProgressItemMap) => {
										return (
											<SidebarItem
												key={item.item_id}
												courseId={courseId}
												id={item.item_id.toString()}
												name={item.item_title}
												contents={item.contents}
											/>
										);
									})}
								</Accordion>
							)}
							{currentTab === 2 && <QuestionList />}
						</Box>
					</Stack>
					<Box
						p="0"
						flexDirection="column"
						alignItems="flex-start"
						justifyContent="flex-start">
						<ButtonGroup d="flex" flex="1" spacing="0" w="full">
							<Button
								leftIcon={<BiAlignLeft />}
								isActive={currentTab === 1}
								sx={buttonStyles}
								onClick={() => setCurrentTab(1)}>
								{__('Lessons', 'masteriyo')}
							</Button>
							{localized.qaEnable && (
								<Button
									leftIcon={<BiInfoCircle />}
									isActive={currentTab === 2}
									sx={buttonStyles}
									onClick={() => setCurrentTab(2)}>
									{__('Questions', 'masteriyo')}
								</Button>
							)}
						</ButtonGroup>
					</Box>
				</Stack>
			</Box>
		</Box>
	);
};

export default Sidebar;
