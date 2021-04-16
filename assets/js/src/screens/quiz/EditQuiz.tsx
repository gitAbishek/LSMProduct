import {
	Box,
	Button,
	ButtonGroup,
	Center,
	Divider,
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
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useMutation, useQuery } from 'react-query';
import { useHistory, useParams } from 'react-router-dom';

import urls from '../../constants/urls';
import API from '../../utils/api';
import Description from './components/Description';
import Name from './components/Name';
import Questions from './components/question/Questions';

const EditQuiz: React.FC = () => {
	const { quizId }: any = useParams();
	const methods = useForm();
	const history = useHistory();
	const toast = useToast();
	const quizAPI = new API(urls.quizes);

	const tabStyles = {
		fontWeight: 'medium',
		py: '4',
	};

	const tabPanelStyles = {
		px: '0',
	};
	// gets total number of content on section
	const quizQuery = useQuery(
		[`quiz${quizId}`, quizId],
		() => quizAPI.get(quizId),
		{
			enabled: !!quizId,
			onError: () => {
				history.goBack();
			},
		}
	);

	const updateQuiz = useMutation(
		(data: object) => quizAPI.update(quizId, data),
		{
			onSuccess: (data: any) => {
				toast({
					title: __('Quiz Added', 'masteriyo'),
					description: data.name + __(' is successfully added.', 'masteriyo'),
					isClosable: true,
					status: 'success',
				});
			},
		}
	);

	const onSubmit = (data: object) => {
		updateQuiz.mutate(data);
	};

	return (
		<FormProvider {...methods}>
			<Box bg="white" p="10" shadow="box">
				{quizQuery.isLoading && (
					<Center minH="xs">
						<Spinner />
					</Center>
				)}
				{quizQuery.isSuccess && (
					<Stack direction="column" spacing="8">
						<Flex aling="center" justify="space-between">
							<Heading as="h1" fontSize="x-large">
								{__('Edit Quiz:', 'masteriyo')} {quizQuery.data.name}
							</Heading>
						</Flex>

						<Tabs>
							<TabList justifyContent="center" borderBottom="1px">
								<Tab sx={tabStyles}>{__('Info', 'masteriyo')}</Tab>
								<Tab sx={tabStyles}>{__('Questions', 'masteriyo')}</Tab>
								<Tab sx={tabStyles}>{__('Settings', 'masteriyo')}</Tab>
							</TabList>
							<TabPanels>
								<TabPanel sx={tabPanelStyles}>
									<form onSubmit={methods.handleSubmit(onSubmit)}>
										<Stack direction="column" spacing="6">
											<Name defaultValue={quizQuery.data.name} />
											<Description defaultValue={quizQuery.data.description} />
											<Box py="3">
												<Divider />
											</Box>
											<ButtonGroup>
												<Button
													colorScheme="blue"
													type="submit"
													isLoading={updateQuiz.isLoading}>
													{__('Update', 'masteriyo')}
												</Button>
												<Button
													variant="outline"
													onClick={() => history.goBack()}>
													{__('Cancel', 'masteriyo')}
												</Button>
											</ButtonGroup>
										</Stack>
									</form>
								</TabPanel>
								<TabPanel sx={tabPanelStyles}>
									<Questions
										courseId={quizQuery.data.course_id}
										quizId={quizId}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</Stack>
				)}
			</Box>
		</FormProvider>
	);
};

export default EditQuiz;
