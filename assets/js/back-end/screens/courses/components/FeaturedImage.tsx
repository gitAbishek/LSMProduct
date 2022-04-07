import {
	Button,
	ButtonGroup,
	Center,
	FormControl,
	FormLabel,
	Image,
	Spinner,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useEffect, useState } from 'react';
import { useFormContext } from 'react-hook-form';
import { useQuery } from 'react-query';
import MediaUploader from '../../../components/common/MediaUploader';
import { MediaSchema } from '../../../schemas';
import MediaAPI from '../../../utils/media';

interface Props {
	defaultValue?: number;
	size?: 'masteriyo_thumbnail' | 'masteriyo_single' | 'full';
}

const FeaturedImage: React.FC<Props> = (props) => {
	const { defaultValue, size = 'full' } = props;
	const [imageId, setImageId] = useState<any>(defaultValue || null);

	const { setValue } = useFormContext();
	const imageAPi = new MediaAPI();

	useEffect(() => {
		setImageId(defaultValue || null);
	}, [defaultValue]);

	const imageQuery = useQuery<MediaSchema>(
		[`featuredImage${imageId}`, imageId],
		() => imageAPi.get(imageId),
		{
			enabled: !!imageId,
			refetchOnWindowFocus: true,
		}
	);

	const onComplete = (imageId: number) => {
		setImageId(imageId);
		setValue('featuredImage', imageId);
	};

	const onDelete = () => {
		setImageId(null);
		setValue('featuredImage', 0);
	};

	return (
		<FormControl>
			<FormLabel>{__('Featured Image', 'masteriyo')}</FormLabel>
			{imageQuery.isLoading && (
				<Center mb="4" mt="4">
					<Spinner />
				</Center>
			)}
			{imageQuery.isSuccess && (
				<Image
					w="full"
					src={
						imageQuery?.data?.media_details?.sizes?.[size]?.source_url
							? imageQuery?.data?.media_details?.sizes?.[size]?.source_url
							: imageQuery?.data?.source_url
					}
					mb="4"
				/>
			)}
			<ButtonGroup d="flex" justifyContent="space-between">
				{imageId && (
					<Button variant="outline" onClick={onDelete} colorScheme="red">
						{__('Remove Featured Image', 'masteriyo')}
					</Button>
				)}
				<MediaUploader
					buttonLabel={
						imageId
							? __('Add New', 'masteriyo')
							: __('Add Featured Image', 'masteriyo')
					}
					modalTitle="Featured Image"
					onSelect={(data: any) => {
						onComplete(data[0].id);
					}}
					isFullWidth={imageId ? false : true}
				/>
			</ButtonGroup>
		</FormControl>
	);
};

export default FeaturedImage;
