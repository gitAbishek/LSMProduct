import {
	Button,
	FormControl,
	FormLabel,
	Image,
	useDisclosure,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { useFormContext } from 'react-hook-form';
import { useQuery } from 'react-query';
import ImageUploadModal from '../../../components/common/ImageUploadModal';
import { MediaSchema } from '../../../schemas';
import MediaAPI from '../../../utils/media';

interface Props {
	defaultValue?: number;
}

const FeaturedImage: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const [imageId, setImageId] = useState<any>(defaultValue || null);
	const { onOpen, onClose, isOpen } = useDisclosure();

	const { setValue } = useFormContext();
	const imageAPi = new MediaAPI();

	const imageQuery = useQuery<MediaSchema>(
		[`featuredImage${imageId}`, imageId],
		() => imageAPi.get(imageId),
		{
			enabled: !!imageId,
		}
	);

	const onComplete = (imageId: number) => {
		console.log(imageId);
		setImageId(imageId);
		setValue('featured_image', imageId);
		onClose();
	};

	return (
		<FormControl>
			<FormLabel>{__('Featured Image', 'masteriyo')}</FormLabel>
			{imageQuery.isSuccess && <Image src={imageQuery?.data?.source_url} />}
			<Button variant="outline" onClick={onOpen} colorScheme="blue">
				{__('Upload Featured Image', 'masteriyo')}
			</Button>
			<ImageUploadModal
				isOpen={isOpen}
				onClose={onClose}
				onComplete={onComplete}
			/>
		</FormControl>
	);
};

export default FeaturedImage;
