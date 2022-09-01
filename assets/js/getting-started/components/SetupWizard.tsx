import { Container, useToast } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import { Step, Steps, useSteps } from 'chakra-ui-steps';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useMutation } from 'react-query';
import urls from '../../back-end/constants/urls';
import API from '../../back-end/utils/api';
import { deepClean } from '../../back-end/utils/utils';
import { Course, Finish, Pages, Payment, Quiz, Welcome } from '../screens';

// Global variable.
declare var _MASTERIYO_: any;

const SetupWizard: React.FC = () => {
	const { nextStep, prevStep, activeStep } = useSteps({
		initialStep: 0,
	});

	const methods = useForm({ reValidateMode: 'onChange', mode: 'onChange' });
	const toast = useToast();
	const settingAPI = new API(urls.settings);

	const addMutation = useMutation((data?: any) => settingAPI.store(data), {
		onSuccess: () => {
			nextStep(); // To finish page.
		},
		onError: (error) => {
			toast({
				title: `${error}. Please try again!!`,
				status: 'error',
				isClosable: true,
			});
		},
	});

	const onSubmit = (data: any) => {
		addMutation.mutate(deepClean(data));
	};

	// Get URL from global var.
	const { adminURL, siteURL, pageBuilderURL } =
		'undefined' != typeof _MASTERIYO_ && _MASTERIYO_;

	let steps = [
		{ label: __('Welcome', 'masteriyo'), StepContent: Welcome },
		{ label: __('Course', 'masteriyo'), StepContent: Course },
		{ label: __('Quiz', 'masteriyo'), StepContent: Quiz },
		{ label: __('Pages', 'masteriyo'), StepContent: Pages },
		{ label: __('Payment', 'masteriyo'), StepContent: Payment },
		{ label: __('Finish', 'masteriyo'), StepContent: Finish },
	];

	return (
		<FormProvider {...methods}>
			<Container maxW="container.md">
				<form onSubmit={methods.handleSubmit(onSubmit)}>
					<Steps
						colorScheme="primary"
						size="sm"
						mb={5}
						activeStep={activeStep}
						labelOrientation="vertical">
						{steps.map(({ label, StepContent }) => (
							<Step label={label} key={label}>
								<StepContent
									dashboardURL={adminURL}
									isButtonLoading={addMutation.isLoading}
									pageBuilderURL={pageBuilderURL}
									siteURL={siteURL}
									prevStep={prevStep}
									nextStep={nextStep}
								/>
							</Step>
						))}
					</Steps>
				</form>
			</Container>
		</FormProvider>
	);
};

export default SetupWizard;
