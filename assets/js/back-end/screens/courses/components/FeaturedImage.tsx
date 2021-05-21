import { FormControl, FormLabel } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import ImageUpload from 'Components/common/ImageUpload';
import React from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	defaultValue?: number;
}

const FeaturedImage: React.FC<Props> = (props) => {
	const { defaultValue } = props;

	const { register, setValue } = useFormContext();
	return (
		<FormControl>
			<FormLabel>{__('Featured Image', 'masteriyo')}</FormLabel>
			<ImageUpload
				name="featured_image"
				register={register}
				setValue={setValue}
				mediaId={defaultValue}
			/>
		</FormControl>
	);
};

export default FeaturedImage;