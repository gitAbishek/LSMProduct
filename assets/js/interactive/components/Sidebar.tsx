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
} from '@chakra-ui/react';
import React from 'react';
import { BiMenu } from 'react-icons/bi';
import { useQuery } from 'react-query';
import API from '../../back-end/utils/api';
import urls from '../../back-end/constants/urls';
import SidebarItem from './SidebarItem';
import { CloseCone } from '../../back-end/constants/images';

const Sidebar = () => {
	const { isOpen, onOpen, onClose } = useDisclosure();
	const courseApi = new API(urls.courses);
	const listApi = new API(urls.builder);
	const courseId = 11;
	const coursesQuery = useQuery(
		[`interactiveCourse${courseId}`, courseId],
		() => courseApi.get(courseId)
	);

	const listQuery = useQuery([`list${courseId}`, courseId], () =>
		listApi.get(courseId)
	);

	if (coursesQuery.isLoading || listQuery.isLoading) {
		return <Spinner />;
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
					</DrawerBody>

					<DrawerFooter></DrawerFooter>
				</DrawerContent>
			</Drawer>
		</Box>
	);
};

export default Sidebar;
