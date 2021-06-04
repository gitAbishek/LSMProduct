import {
	Box,
	Drawer,
	DrawerContent,
	useDisclosure,
	DrawerCloseButton,
	DrawerBody,
	Button,
	DrawerHeader,
	DrawerFooter,
	IconButton,
	Accordion,
	AccordionButton,
	AccordionIcon,
	AccordionItem,
	AccordionPanel,
	Spinner,
	DrawerOverlay,
	list,
} from '@chakra-ui/react';
import React from 'react';
import { BiMenu } from 'react-icons/bi';
import { useQuery } from 'react-query';
import API from '../../back-end/utils/api';
import urls from '../../back-end/constants/urls';

const Sidebar = () => {
	const { isOpen, onOpen, onClose } = useDisclosure();
	const courseApi = new API(urls.courses);
	const listApi = new API(urls.builder);
	const courseId = 11;
	const coursesQuery = useQuery(
		[`interactiveCourse${courseId}`, courseId],
		() => courseApi.get(courseId)
	);

	const listQuery = useQuery(
		[`list${courseId}`, courseId],
		() => listApi.get(courseId),
		{
			onSuccess: (data) => {
				console.log(data);
			},
		}
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
				{/* <DrawerOverlay /> */}
				<DrawerContent>
					<DrawerCloseButton />
					<DrawerHeader bg="blue.500" color="white" fontSize="md">
						{coursesQuery.data.name}
					</DrawerHeader>
					<DrawerBody px="0">
						<Accordion allowToggle>
							{listQuery.data.section_order.map((sectionId: any) => {
								const section = listQuery.data.sections[sectionId];

								return (
									<AccordionItem key={section.id}>
										{console.log(section)}
										<h2>
											<AccordionButton>
												<Box flex="1" textAlign="left">
													{section.name}
												</Box>
												<AccordionIcon />
											</AccordionButton>
										</h2>
										<AccordionPanel pb={4}>
											Lorem ipsum dolor sit amet, consectetur adipiscing elit,
											sed do eiusmod tempor incididunt ut labore et dolore magna
											aliqua. Ut enim ad minim veniam, quis nostrud exercitation
											ullamco laboris nisi ut aliquip ex ea commodo consequat.
										</AccordionPanel>
									</AccordionItem>
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
