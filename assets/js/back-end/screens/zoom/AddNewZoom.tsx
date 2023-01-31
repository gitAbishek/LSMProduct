import { Box, Container, Flex, Heading, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import BackToBuilder from '../../components/common/BackToBuilder';
import Buttons from './components/Buttons';
import CloseRegistration from './components/CloseRegistration';
import Description from './components/Description';
import FocusMode from './components/FocusMode';
import JoinBeforeTheHost from './components/JoinBeforeTheHost';
import MuteUponEntry from './components/MuteUponEntry';
import Name from './components/Name';
import ParticipantVideo from './components/ParticipantVideo';
import Passwords from './components/Passwords';
import RecordingSetting from './components/RecordingSetting';
import TimeDuration from './components/TimeDuration';

interface Props {
	id: string;
	name: any;
	defaultValue?: string;
	height?: number;
}

const AddNewZoom: React.FC = () => {
	const methods = useForm();
	return (
		<Container maxW="container.xl">
			<Stack direction="column" spacing="6">
				<BackToBuilder />
				<FormProvider {...methods}>
					<form
						onSubmit={methods.handleSubmit((data: any) => console.log(data))}>
						<Stack
							direction={['column', 'column', 'column', 'row']}
							spacing="8">
							<Box
								flex="1"
								bg="white"
								p="10"
								shadow="box"
								d="flex"
								flexDirection="column"
								justifyContent="space-between">
								<Stack direction="column" spacing="8">
									<Flex align="center" justify="space-between">
										<Heading as="h1" fontSize="x-large">
											{__('Add New Zoom Integration')}
										</Heading>
									</Flex>

									<Stack direction="column" spacing="6">
										<Name />
										<Description />
										<Passwords />
										<Buttons />
									</Stack>
								</Stack>
							</Box>
							<Box w={{ lg: '400px' }} bg="white" p="10" shadow="box">
								<Stack direction="column" spacing="6">
									<JoinBeforeTheHost />
									<ParticipantVideo />
									<MuteUponEntry />
									<FocusMode />
									<RecordingSetting />
									<TimeDuration />
									<CloseRegistration />
								</Stack>
							</Box>
						</Stack>
					</form>
				</FormProvider>
			</Stack>
		</Container>
	);
};

export default AddNewZoom;
