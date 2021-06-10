import { Container, useToast } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useMutation } from 'react-query';

import urls from '../../back-end/constants/urls';
import API from '../../back-end/utils/api';
import * as screens from '../screens';
import { Step, Steps, useSteps } from 'chakra-ui-steps';

// Global variable.
declare var _MASTERIYO_: any;

const SetupWizard: React.FC = () => {
	const { nextStep, prevStep, activeStep } = useSteps({
		initialStep: 0,
	});

	const methods = useForm();
	const toast = useToast();
	const settingAPI = new API(urls.settings);

	const addMutation = useMutation((data?: any) => settingAPI.store(data), {
		onSuccess: (data) => {
			nextStep(); // To finish page.

			toast({
				title: `Global setting successfully updated`,
				status: 'success',
				isClosable: true,
			});
		},
		onError: (error) => {
			toast({
				title: `${error}. Please try again!!`,
				status: 'error',
				isClosable: true,
			});
		},
	});

	const onSubmit = (data?: any) => {
		addMutation.mutate(data);
	};

	// Get URL from global var.
	const { adminURL, siteURL, pageBuilderURL } =
		'undefined' != typeof _MASTERIYO_ && _MASTERIYO_;

	let steps = [];
	for (const [key, RenderContent] of Object.entries(screens)) {
		steps.push({ label: key, RenderContent });
	}

	return (
		<FormProvider {...methods}>
			<Container maxW="container.md">
				<form onSubmit={methods.handleSubmit(onSubmit)}>
					<Steps size="sm" mb={5} activeStep={activeStep}>
						{steps.map(({ label, RenderContent }) => (
							<Step label={label} key={label}>
								<RenderContent
									dashboardURL={adminURL}
									mutationLoading={addMutation.isLoading}
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
