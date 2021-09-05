import {
	Accordion,
	Box,
	Button,
	ButtonGroup,
	Heading,
	Icon,
	IconButton,
	Link,
	Stack,
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
import QuestionList from './qa/QuestionList';
import SidebarItem from './SidebarItem';

interface Props {
	items: [CourseProgressItemMap];
	name: string;
	onToggle: () => void;
	isOpen: boolean;
	isHeaderOpen: boolean;
	coursePermalink: string;
}

const Sidebar: React.FC<Props> = (props) => {
	const { items, name, onToggle, isOpen, isHeaderOpen, coursePermalink } =
		props;
	const { courseId }: any = useParams();

	const [currentTab, setCurrentTab] = useState<number>(1);

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

	return (
		<Box
			pos="fixed"
			top={isHeaderOpen ? '66px' : 0}
			left="0"
			w="300px"
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
					right="-35px"
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
					<Stack direction="column" spacing="0" flex="1">
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
								<Icon as={BiChevronLeft} fontSize="sm" />
								{__('Back to course', 'masteriyo')}
							</Link>
						</Stack>
						<Box p="0" position="relative" overflowX="hidden" flex="1">
							{currentTab === 1 && (
								<Accordion allowToggle>
									{items.map((item: CourseProgressItemMap) => {
										return (
											<SidebarItem
												key={item.item_id}
												courseId={courseId}
												id={item.item_id}
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
							<Button
								leftIcon={<BiInfoCircle />}
								isActive={currentTab === 2}
								sx={buttonStyles}
								onClick={() => setCurrentTab(2)}>
								{__('Questions', 'masteriyo')}
							</Button>
						</ButtonGroup>
					</Box>
				</Stack>
			</Box>
		</Box>
	);
};

export default Sidebar;
