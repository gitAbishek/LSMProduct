import {
	Box,
	Button,
	ButtonGroup,
	Center,
	Flex,
	Heading,
	Spinner,
	Stack,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useMutation, useQuery } from 'react-query';
import { useHistory, useParams } from 'react-router-dom';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import { mergeDeep } from '../../utils/mergeDeep';
import Description from './components/Description';
import Name from './components/Name';

const AddNewQuiz: React.FC = () => {
	const { sectionId }: any = useParams();
	const methods = useForm();
	const history = useHistory();
	const toast = useToast();
	const contentAPI = new API(urls.contents);
	const quizAPI = new API(urls.quizes);
	const sectionsAPI = new API(urls.sections);
	const [totalContentCount, setTotalContentCount] = useState<any>(0);
	const [courseId, setCourseId] = useState<any>(null);

	const tabStyles = {
		fontWeight: 'medium',
		py: '4',
	};

	// gets total number of content on section
	const contentQuery = useQuery(
		[`content${sectionId}`, sectionId],
		() => contentAPI.list({ section: sectionId }),
		{
			enabled: !!sectionId,
			onSuccess: (data: any) => {
				setTotalContentCount(data.length);
			},
			onError: () => {
				history.push(routes.notFound);
			},
		}
	);

	const sectionQuery = useQuery(
		[`section${sectionId}`, sectionId],
		() => sectionsAPI.get(sectionId),
		{
			enabled: !!sectionId,
			onSuccess: (data: any) => {
				setCourseId(data.course_id);
			},
			onError: () => {
				history.push(routes.notFound);
			},
		}
	);

	const addQuiz = useMutation((data: object) => quizAPI.store(data), {
		onSuccess: (data: any) => {
			toast({
				title: __('Quiz Added', 'masteriyo'),
				description: data.name + __(' is successfully added.', 'masteriyo'),
				isClosable: true,
				status: 'success',
			});
			history.push(routes.section.replace(':courseId', data.course_id));
		},
	});

	const onSubmit = (data: object) => {
		const newData = {
			course_id: courseId,
			parent_id: sectionId,
			menu_order: totalContentCount + 1,
		};

		addQuiz.mutate(mergeDeep(data, newData));
	};

	return (
		<FormProvider {...methods}>
			<Box bg="white" p="10" shadow="box">
				{(contentQuery.isLoading || sectionQuery.isLoading) && (
					<Center minH="xs">
						<Spinner />
					</Center>
				)}
				{(contentQuery.isSuccess || sectionQuery.isSuccess) && (
					<Stack direction="column" spacing="8">
						<Flex aling="center" justify="space-between">
							<Heading as="h1" fontSize="x-large">
								{__('Add New Quiz', 'masteriyo')}
							</Heading>
						</Flex>

						<form onSubmit={methods.handleSubmit(onSubmit)}>
							<Stack direction="column" spacing="6">
								<Tabs>
									<TabList justifyContent="center" borderBottom="1px">
										<Tab sx={tabStyles}>{__('Info', 'masteriyo')}</Tab>
										<Tab sx={tabStyles} isDisabled>
											{__('Questions', 'masteriyo')}
										</Tab>
										<Tab sx={tabStyles} isDisabled>
											{__('Settings', 'masteriyo')}
										</Tab>
									</TabList>
									<TabPanels>
										<TabPanel px="0">
											<Stack direction="column" spacing="6">
												<Name />
												<Description />
											</Stack>
										</TabPanel>
									</TabPanels>
								</Tabs>

								<ButtonGroup>
									<Button
										colorScheme="blue"
										type="submit"
										isLoading={addQuiz.isLoading}>
										{__('Add New Quiz', 'masteriyo')}
									</Button>
									<Button variant="outline" onClick={() => history.goBack()}>
										{__('Cancel', 'masteriyo')}
									</Button>
								</ButtonGroup>
							</Stack>
						</form>
					</Stack>
				)}
			</Box>
		</FormProvider>
	);
};

export default AddNewQuiz;
