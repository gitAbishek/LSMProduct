import {
	Box,
	Drawer,
	DrawerContent,
	useDisclosure,
	DrawerCloseButton,
	DrawerBody,
	DrawerHeader,
	DrawerFooter,
	IconButton,
	Accordion,
	Spinner,
	DrawerOverlay,
	Alert,
	AlertTitle,
	ButtonGroup,
	Button,
} from '@chakra-ui/react';
import React, { useState } from 'react';
import { BiAlignLeft, BiInfoCircle, BiMenu } from 'react-icons/bi';
import { useQuery } from 'react-query';
import API from '../../back-end/utils/api';
import urls from '../../back-end/constants/urls';
import SidebarItem from './SidebarItem';
import { CloseCone } from '../../back-end/constants/images';
import { __ } from '@wordpress/i18n';

const Sidebar = () => {
	const { isOpen, onOpen, onClose } = useDisclosure();
	const [currentTab, setCurrentTab] = useState<number>(1);
	const courseId = 8;

	const courseApi = new API(urls.courses);
	const listApi = new API(urls.builder);

	const coursesQuery = useQuery(
		[`interactiveCourse${courseId}`, courseId],
		() => courseApi.get(courseId)
	);

	const listQuery = useQuery([`list${courseId}`, courseId], () =>
		listApi.get(courseId)
	);

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

	if (coursesQuery.isLoading || listQuery.isLoading) {
		return <Spinner />;
	}

	if (coursesQuery.isError || listQuery.isError) {
		return (
			<Alert status="error">
				<AlertTitle>{__('No courses found', 'masteriyo')}</AlertTitle>
			</Alert>
		);
	}

	return (
		<Box>
			<IconButton
				colorScheme="blue"
				position="fixed"
				top="32"
				fontSize="xl"
				icon={<BiMenu />}
				onClick={onOpen}
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
						{coursesQuery.data.name}
					</DrawerHeader>

					<DrawerBody p="0">
						{currentTab === 1 && (
							<Accordion allowToggle>
								{listQuery.data.section_order.map((sectionId: any) => {
									const section = listQuery.data.sections[sectionId];
									return (
										<SidebarItem
											key={section.id}
											id={section.id}
											name={section.name}
											contents={section.contents}
											contentsMap={listQuery.data.contents}
										/>
									);
								})}
							</Accordion>
						)}
						{currentTab === 2 && <span>Questions</span>}
					</DrawerBody>

					<DrawerFooter p="0">
						<ButtonGroup d="flex" flex="1" spacing="0">
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
