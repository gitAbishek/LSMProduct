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
} from '@chakra-ui/react';
import React from 'react';
import { BiMenu } from 'react-icons/bi';
import { useQuery } from 'react-query';
import API from '../../back-end/utils/api';
import urls from '../../back-end/constants/urls';

const Sidebar = () => {
	const { isOpen, onOpen, onClose } = useDisclosure();
	const courseApi = new API(urls.courses);

	const coursesQuery = useQuery([`interactiveCourse11`], () =>
		courseApi.get(11)
	);

	if (coursesQuery.isLoading) {
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
				<DrawerContent>
					<DrawerCloseButton />
					<DrawerHeader bg="blue.500" color="white" fontSize="md">
						{coursesQuery.data.name}
					</DrawerHeader>

					<DrawerBody px="0">
						<Accordion>
							<AccordionItem>
								<h2>
									<AccordionButton>
										<Box flex="1" textAlign="left">
											Section 1 title
										</Box>
										<AccordionIcon />
									</AccordionButton>
								</h2>
								<AccordionPanel pb={4}>
									Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
									do eiusmod tempor incididunt ut labore et dolore magna aliqua.
									Ut enim ad minim veniam, quis nostrud exercitation ullamco
									laboris nisi ut aliquip ex ea commodo consequat.
								</AccordionPanel>
							</AccordionItem>

							<AccordionItem>
								<h2>
									<AccordionButton>
										<Box flex="1" textAlign="left">
											Section 2 title
										</Box>
										<AccordionIcon />
									</AccordionButton>
								</h2>
								<AccordionPanel pb={4}>
									Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
									do eiusmod tempor incididunt ut labore et dolore magna aliqua.
									Ut enim ad minim veniam, quis nostrud exercitation ullamco
									laboris nisi ut aliquip ex ea commodo consequat.
								</AccordionPanel>
							</AccordionItem>
						</Accordion>
					</DrawerBody>

					<DrawerFooter>
						<Button variant="outline" mr={3} onClick={onClose}>
							Cancel
						</Button>
						<Button colorScheme="blue">Save</Button>
					</DrawerFooter>
				</DrawerContent>
			</Drawer>
		</Box>
	);
};

export default Sidebar;
