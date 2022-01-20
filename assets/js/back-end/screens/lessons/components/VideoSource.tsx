import {
	AspectRatio,
	Button,
	ButtonGroup,
	Center,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Input,
	Select,
	Spinner,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useEffect, useState } from 'react';
import { useFormContext, useWatch } from 'react-hook-form';
import { useQuery } from 'react-query';
import MediaUploader from '../../../components/common/MediaUploader';
import { MediaSchema } from '../../../schemas';
import MediaAPI from '../../../utils/media';

interface Props {
	defaultSource?: any;
	defaultSourceUrl?: any;
	defaultSourceID?: number;
}
const VideoSource: React.FC<Props> = (props) => {
	const { defaultSource, defaultSourceUrl, defaultSourceID } = props;
	const [videoId, setVideoId] = useState<any>(null);
	const {
		register,
		control,
		formState: { errors },
		setValue,
	} = useFormContext();

	const imageAPi = new MediaAPI();

	useEffect(() => {
		setVideoId(defaultSourceID || null);
	}, [defaultSourceID]);

	const mediaQuery = useQuery<MediaSchema>(
		[`videoSource${videoId}`, videoId],
		() => imageAPi.get(videoId),
		{
			enabled: !!videoId,
			refetchOnWindowFocus: true,
		}
	);

	const watchSource = useWatch({
		name: 'video_source',
		defaultValue: defaultSource || 'self-hosted',
		control,
	});

	const onComplete = (videoId: number) => {
		setVideoId(videoId);
		setValue('video_source_url', videoId.toString());
	};

	const onDelete = () => {
		setVideoId(null);
		setValue('video_source_url', ' ');
	};

	return (
		<Stack direction="row" spacing="6">
			<FormControl>
				<FormLabel>{__('Video Source', 'masteriyo')}</FormLabel>
				<Select {...register('video_source')} defaultValue={defaultSource}>
					<option value="self-hosted">{__('Self Hosted', 'masteriyo')}</option>
					<option value="youtube">{__('YouTube', 'masteriyo')}</option>
					<option value="vimeo">{__('Vimeo', 'masteriyo')}</option>
				</Select>
			</FormControl>
			{watchSource !== 'self-hosted' && (
				<FormControl isInvalid={!!errors.video_source_url}>
					<FormLabel>{__('Video Source URL', 'masteriyo')}</FormLabel>
					<Input
						type="text"
						defaultValue={defaultSourceUrl}
						{...register('video_source_url', {
							pattern: {
								value:
									watchSource === 'youtube'
										? /\/\/(?:www\.)?youtu(?:\.be|be\.com)\/(?:watch\?v=|embed\/)?([a-z0-9_\-]+)/i
										: /\/\/(?:www\.)?vimeo.com\/([0-9a-z\-_]+)/i,
								message: __('Please Provide Valid URL.', 'masteriyo'),
							},
						})}
					/>
					<FormErrorMessage>
						{errors?.video_source_url && errors?.video_source_url?.message}
					</FormErrorMessage>
				</FormControl>
			)}
			{watchSource === 'self-hosted' && (
				<FormControl>
					<FormLabel>{__('Self Hosted Video', 'masteriyo')}</FormLabel>
					{mediaQuery.isLoading && (
						<Center mb="4" mt="4">
							<Spinner />
						</Center>
					)}
					{mediaQuery.isSuccess && (
						<AspectRatio ratio={16 / 9} mb="4">
							<video
								src={mediaQuery?.data?.source_url}
								title={mediaQuery?.data?.title?.rendered}
								controls
							/>
						</AspectRatio>
					)}
					<ButtonGroup d="flex" justifyContent="space-between">
						{videoId && (
							<Button variant="outline" onClick={onDelete} colorScheme="red">
								{__('Remove Video', 'masteriyo')}
							</Button>
						)}
						<MediaUploader
							buttonLabel={
								videoId
									? __('Add New Video', 'masteriyo')
									: __('Add Video', 'masteriyo')
							}
							modalTitle={__('Self Hosted Video', 'masteriyo')}
							onSelect={(data: any) => {
								onComplete(data[0].id);
							}}
							isFullWidth={videoId ? false : true}
							mediaType="video"
						/>
					</ButtonGroup>
				</FormControl>
			)}
		</Stack>
	);
};

export default VideoSource;
