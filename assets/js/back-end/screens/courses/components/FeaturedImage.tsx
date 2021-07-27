import {
	Button,
	ButtonGroup,
	FormControl,
	FormLabel,
	Image,
	Stack,
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
		setImageId(imageId);
		setValue('featured_image', imageId);
		onClose();
	};

	const removeImage = () => {
		setImageId(null);
	};

	return (
		<FormControl>
			<FormLabel>{__('Featured Image', 'masteriyo')}</FormLabel>

			{imageQuery.isSuccess ? (
				<Stack direction="column" spacing="4">
					<Image src={imageQuery?.data?.source_url} />
					<ButtonGroup d="flex" justifyContent="space-between">
						<Button variant="outline" onClick={removeImage} colorScheme="red">
							{__('Remove Featured Image', 'masteriyo')}
						</Button>
						<Button variant="outline" onClick={onOpen} colorScheme="blue">
							{__('Add New', 'masteriyo')}
						</Button>
					</ButtonGroup>
				</Stack>
			) : (
				<ButtonGroup d="flex" justifyContent="space-between">
					<Button
						variant="outline"
						isFullWidth
						onClick={onOpen}
						colorScheme="blue">
						{__('Add Featured Image', 'masteriyo')}
					</Button>
				</ButtonGroup>
			)}
			<ImageUploadModal
				isOpen={isOpen}
				onClose={onClose}
				onComplete={onComplete}
				selected={imageId}
			/>
		</FormControl>
	);
};

export default FeaturedImage;
