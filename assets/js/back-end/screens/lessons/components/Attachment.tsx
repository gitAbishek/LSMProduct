import {
	Button,
	ButtonGroup,
	FormControl,
	FormLabel,
	HStack,
	Icon,
	Spinner,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useEffect, useState } from 'react';
import { useFormContext } from 'react-hook-form';
import { FaFileAlt } from 'react-icons/fa';
import { useQuery } from 'react-query';
import MediaUploader from '../../../components/common/MediaUploader';
import { MediaSchema } from '../../../schemas';
import MediaAPI from '../../../utils/media';
import { getFileNameFromURL } from '../../../utils/utils';

interface Props {
	defaultSourceID?: number;
}
const Attachment: React.FC<Props> = (props) => {
	const { defaultSourceID } = props;
	const [attachmentId, setAttachmentId] = useState<any>(null);
	const { setValue } = useFormContext();

	const attachmentAPI = new MediaAPI();

	useEffect(() => {
		setAttachmentId(defaultSourceID || null);
	}, [defaultSourceID]);

	const mediaQuery = useQuery<MediaSchema>(
		[`attachment${attachmentId}`, attachmentId],
		() => attachmentAPI.get(attachmentId),
		{
			enabled: !!attachmentId,
			refetchOnWindowFocus: true,
		}
	);

	const onComplete = (attachId: number) => {
		setAttachmentId(attachId);
		setValue('attachments.0.id', attachId);
	};

	const onDelete = () => {
		setAttachmentId(null);
		setValue('attachments.0.id', ' ');
	};

	return (
		<FormControl>
			<FormLabel>{__('Attachment', 'masteriyo')}</FormLabel>

			<Stack direction="column" spacing="4">
				{mediaQuery.isLoading && <Spinner />}

				{mediaQuery.isSuccess && (
					<HStack align="flex-start">
						<Icon color="blue.500" as={FaFileAlt} />
						<Text color="gray.600" fontSize="md">
							{getFileNameFromURL(mediaQuery?.data?.source_url)}
						</Text>
					</HStack>
				)}
				<ButtonGroup>
					{attachmentId && (
						<Button variant="outline" onClick={onDelete} colorScheme="red">
							{__('Remove', 'masteriyo')}
						</Button>
					)}
					<MediaUploader
						buttonLabel={
							attachmentId
								? __('Upload New', 'masteriyo')
								: __('Upload', 'masteriyo')
						}
						modalTitle={__('Lesson Attachment', 'masteriyo')}
						onSelect={(data: any) => {
							onComplete(data[0].id);
						}}
						isFullWidth={false}
						mediaType="[
						application/pdf, 
						application/zip, 
						application/msword, 
						application/vnd.openxmlformats-officedocument.wordprocessingml.document, 
						application/vnd.ms-powerpoint,
						application/vnd.openxmlformats-officedocument.presentationml.presentation,
						audio
					]"
					/>
				</ButtonGroup>
			</Stack>
		</FormControl>
	);
};

export default Attachment;
