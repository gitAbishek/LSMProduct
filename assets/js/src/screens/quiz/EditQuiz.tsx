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
import Description from './components/Description';
import Name from './components/Name';

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
								{__('Add New Quiz', 'masteriyo')}
							</Heading>
						</Flex>

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
									<form onSubmit={methods.handleSubmit(onSubmit)}>
										<Stack direction="column" spacing="6">
											<Name defaultValue={quizQuery.data.name} />
											<Description defaultValue={quizQuery.data.description} />
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
							</TabPanels>
						</Tabs>
					</Stack>
				)}
			</Box>
		</FormProvider>
	);
};

export default EditQuiz;
