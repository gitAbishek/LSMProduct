import { Button } from '@chakra-ui/react';
import React from 'react';
import { __ } from '@wordpress/i18n';

const GettingStarted = () => {
	return (
		<div className="mto-flex mto-items-center mto-content-center mto-text-center">
			<h1 className="mto-text-lg mto-font-medium mto-mb-5">
				{__('Add your content', 'masteriyo')}
			</h1>
			<p className="mto-mb-16">
				{__('Upload your content, quiz, assignment', 'masteriyo')}
			</p>
			<div className="mto-flex -mto-ml-8 -mto-mr-8">
				<Button className="mto-mx-8">{__('Add Section', 'masteriyo')}</Button>
				<Button className="mto-mx-8">
					{__('Add Assignment', 'masteriyo')}
				</Button>
				<Button className="mto-mx-8">{__('Add Quiz', 'masteriyo')}</Button>
			</div>
			<p className="mto-mb-16 mto-leading-6">
				{__('Not sure how to get started?', 'masteriyo')} <br />
				{__('Learn about it in our', 'masteriyo')}{' '}
				<a
					href="themegrill.com/masteriyo/getting-started"
					className="mto-font-bold">
					{__('Documentation', 'masteriyo')}
				</a>
			</p>
		</div>
	);
};

export default GettingStarted;
