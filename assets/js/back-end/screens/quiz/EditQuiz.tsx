import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Divider,
	Flex,
	Heading,
	Stack,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import FullScreenLoader from 'Components/layout/FullScreenLoader';
import HeaderBuilder from 'Components/layout/HeaderBuilder';
import React, { useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useMutation, useQuery } from 'react-query';
import { useHistory, useParams } from 'react-router-dom';

import routes from '../../constants/routes';
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
	const [courseId, setCourseId] = useState<any>(null);
	const [tabIndex, setTabIndex] = useState<number>(0);

	const tabStyles = {
		fontWeight: 'medium',
		py: '4',
	};

	const tabPanelStyles = {
		p: '0',
	};
	// gets total number of content on section
	const quizQuery = useQuery(
		[`quiz${quizId}`, quizId],
		() => quizAPI.get(quizId),
		{
			enabled: !!quizId,
			onError: () => {
				history.push(routes.notFound);
			},
			onSuccess: (data: any) => {
				setCourseId(data.course_id);
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
				history.push(routes.builder.replace(':courseId', data.course_id));
			},
		}
	);

	const onSubmit = (data: object) => {
		updateQuiz.mutate(data);
	};

	if (quizQuery.isLoading) {
		return <FullScreenLoader />;
	}

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<HeaderBuilder courseId={courseId} />
			<Container maxW="container.xl">
				<FormProvider {...methods}>
					<Box bg="white" p="10" shadow="box">
						<Stack direction="column" spacing="8">
							<Flex aling="center" justify="space-between">
								<Heading as="h1" fontSize="x-large">
									{__('Edit Quiz:', 'masteriyo')} {quizQuery.data.name}
								</Heading>
							</Flex>

							<Tabs index={tabIndex} onChange={(index) => setTabIndex(index)}>
								<TabList justifyContent="center" borderBottom="1px">
									<Tab sx={tabStyles}>{__('Info', 'masteriyo')}</Tab>
									<Tab sx={tabStyles}>{__('Settings', 'masteriyo')}</Tab>
									<Tab sx={tabStyles}>{__('Questions', 'masteriyo')}</Tab>
								</TabList>
								<TabPanels>
									<TabPanel sx={tabPanelStyles}></TabPanel>
									<TabPanel sx={tabPanelStyles}>settings</TabPanel>
									<TabPanel sx={tabPanelStyles}>
										<Questions
											courseId={quizQuery.data.course_id}
											quizId={quizId}
										/>
									</TabPanel>
								</TabPanels>
							</Tabs>
							<form onSubmit={methods.handleSubmit(onSubmit)}>
								<Stack direction="column" spacing="6">
									{tabIndex === 0 && (
										<>
											<Name defaultValue={quizQuery.data.name} />
											<Description defaultValue={quizQuery.data.description} />
										</>
									)}

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
											onClick={() =>
												history.push(
													routes.builder.replace(':courseId', courseId)
												)
											}>
											{__('Cancel', 'masteriyo')}
										</Button>
									</ButtonGroup>
								</Stack>
							</form>
						</Stack>
					</Box>
				</FormProvider>
			</Container>
		</Stack>
	);
};

export default EditQuiz;
