import { Heading, Icon, Link, Stack, Text } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { FaFileDownload } from 'react-icons/fa';
import { LessonSchema } from '../../../back-end/schemas';
import { getFileNameFromURL } from '../../../back-end/utils/utils';

interface Props {
	lessonQuery: LessonSchema;
}

interface AttachmentSchema {
	id: number;
	url: string;
	title?: string;
}

const LessonAttachment: React.FC<Props> = (props) => {
	const { lessonQuery } = props;
	return (
		<Stack direction="column" spacing="4">
			<Heading
				fontSize="xl"
				as="h5"
				pb="4"
				borderBottom="1px"
				borderColor="gray.100">
				{__('Lesson Material', 'masteriyo')}
			</Heading>
			{lessonQuery?.attachments?.map((attachment: AttachmentSchema) => {
				return (
					<Stack key={attachment?.id} direction="row" align="center">
						<Icon color="blue.400" as={FaFileDownload} />
						<Link href={attachment?.url} title={attachment?.title}>
							<Text color="blue.500">
								{getFileNameFromURL(attachment?.url)}
							</Text>
						</Link>
					</Stack>
				);
			})}
		</Stack>
	);
};

export default LessonAttachment;
