import {
	Accordion,
	Box,
	Button,
	ButtonGroup,
	Drawer,
	DrawerBody,
	DrawerCloseButton,
	DrawerContent,
	DrawerFooter,
	DrawerHeader,
	DrawerOverlay,
	IconButton,
	useDisclosure,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { BiAlignLeft, BiInfoCircle, BiMenu } from 'react-icons/bi';
import { useParams } from 'react-router-dom';
import { CloseCone } from '../../back-end/constants/images';
import { CourseProgressItemMap } from '../schemas';
import QuestionList from './qa/QuestionList';
import SidebarItem from './SidebarItem';

interface Props {
	items: [CourseProgressItemMap];
	name: string;
}
const Sidebar: React.FC<Props> = (props) => {
	const { items, name } = props;
	const { courseId }: any = useParams();

	const { isOpen, onOpen, onClose } = useDisclosure();
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
		<Box>
			<IconButton
				variant="unstyled"
				color="white"
				d="flex"
				justifyContent="center"
				position="fixed"
				top="32"
				fontSize="xl"
				icon={<BiMenu />}
				onClick={onOpen}
				bgSize="cover"
				minW="auto"
				w="36px"
				p="0"
				h="60px"
				bgImage={`url(${CloseCone})`}
				aria-label="open sidebar"
			/>
			<Drawer isOpen={isOpen} placement="left" onClose={onClose}>
				<DrawerOverlay bg="rgba(255,255,255,0.1)" />
				<DrawerContent>
					<DrawerCloseButton
						sx={{
							position: 'absolute',
							right: '-35px',
							backgroundImage: `url(${CloseCone})`,
							backgroundSize: 'cover',
							width: '36px',
							height: '60px',
							color: 'white',
							fontSize: '12',
							top: '128px',
						}}
						_hover={{
							backgroundImage: `url(${CloseCone})`,
						}}
					/>
					<DrawerHeader
						bg="blue.500"
						color="white"
						fontSize="lg"
						minH="20"
						d="flex"
						alignItems="center"
						fontWeight="semibold">
						{name}
					</DrawerHeader>

					<DrawerBody p="0" position="relative" overflowX="hidden">
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
					</DrawerBody>

					<DrawerFooter
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
					</DrawerFooter>
				</DrawerContent>
			</Drawer>
		</Box>
	);
};

export default Sidebar;
