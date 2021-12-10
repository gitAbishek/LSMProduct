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
		<>
			<Heading fontSize="lg" as="h5">
				{__('Lesson Material', 'masteriyo')}
			</Heading>
			{lessonQuery?.attachments?.map((attachment: AttachmentSchema) => {
				return (
					<Stack key={attachment?.id} direction="row">
						<Icon color="blue.400" as={FaFileDownload} />
						<Link href={attachment?.url} title={attachment?.title}>
							<Text color="blue.500">
								{getFileNameFromURL(attachment?.url)}
							</Text>
						</Link>
					</Stack>
				);
			})}
		</>
	);
};

export default LessonAttachment;
