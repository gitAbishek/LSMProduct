import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Flex,
	Heading,
	Icon,
	Image,
	Stack,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiBook, BiCog, BiEdit } from 'react-icons/bi';
import { useMutation } from 'react-query';
import { Link as RouterLink, useHistory } from 'react-router-dom';

import { Logo } from '../../constants/images';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import { mergeDeep } from '../../utils/mergeDeep';
import CourseSetting from '../builder/component/CourseSetting';
import Categories from './components/Categories';
import Description from './components/Description';
import FeaturedImage from './components/FeaturedImage';
import Name from './components/Name';
import Price from './components/Price';

const AddNewCourse: React.FC = () => {
	const history = useHistory();
	const methods = useForm();
	const courseAPI = new API(urls.courses);

	const tabStyles = {
		fontWeight: 'medium',
		fontSize: 'sm',
		py: '6',
		px: 0,
		mx: 4,
	};

	const tabPanelStyles = {
		px: '0',
	};

	const iconStyles = {
		mr: '2',
	};

	const addMutation = useMutation((data) => courseAPI.store(data), {
		onSuccess: (data) => {
			history.push(routes.builder.replace(':courseId', data.id));
		},
	});

	const onSubmit = (data: any) => {
		const newData: any = {
			...(data.categories && {
				categories: data.categories.map((category: any) => ({
					id: category.value,
				})),
			}),
			...(data.regular_price && {
				regular_price: data.regular_price.toString(),
			}),
		};

		addMutation.mutate(mergeDeep(data, newData));
	};

	return (
		<FormProvider {...methods}>
			<Tabs>
				<Stack direction="column" spacing="8" alignItems="center">
					<Box bg="white" w="full">
						<Container maxW="container.xl">
							<Flex
								direction="row"
								justifyContent="space-between"
								align="center">
								<Stack direction="row" spacing="12" align="center">
									<Box>
										<RouterLink to={routes.courses.list}>
											<Image src={Logo} alt="Masteriyo Logo" w="120px" />
										</RouterLink>
									</Box>
									<TabList borderBottom="none" bg="white">
										<Tab sx={tabStyles}>
											<Icon as={BiBook} sx={iconStyles} />
											{__('Course', 'masteriyo')}
										</Tab>
										<Tab sx={tabStyles} isDisabled>
											<Icon as={BiEdit} sx={iconStyles} />
											{__('Builder', 'masteriyo')}
										</Tab>
										<Tab sx={tabStyles}>
											<Icon as={BiCog} sx={iconStyles} />
											{__('Settings', 'masteriyo')}
										</Tab>
									</TabList>
								</Stack>
								<ButtonGroup>
									<Button
										colorScheme="blue"
										onClick={methods.handleSubmit(onSubmit)}
										isLoading={addMutation.isLoading}>
										{__('Add Course', 'masteriyo')}
									</Button>
								</ButtonGroup>
							</Flex>
						</Container>
					</Box>
					<Container maxW="container.xl">
						<Stack direction="column" spacing="8">
							<Heading as="h1" size="xl">
								{__('Add New Course', 'masteriyo')}
							</Heading>
							<TabPanels>
								<TabPanel sx={tabPanelStyles}>
									<form>
										<Stack direction="row" spacing="8">
											<Box
												flex="1"
												bg="white"
												p="10"
												shadow="box"
												d="flex"
												flexDirection="column"
												justifyContent="space-between">
												<Stack direction="column" spacing="6">
													<Name />
													<Description />
												</Stack>
											</Box>
											<Box w="400px" bg="white" p="10" shadow="box">
												<Stack direction="column" spacing="6">
													<Price />
													<Categories />
													<FeaturedImage />
												</Stack>
											</Box>
										</Stack>
									</form>
								</TabPanel>
								<TabPanel></TabPanel>
								<TabPanel sx={tabPanelStyles}>
									<CourseSetting />
								</TabPanel>
							</TabPanels>
						</Stack>
					</Container>
				</Stack>
			</Tabs>
		</FormProvider>
	);
};

export default AddNewCourse;
