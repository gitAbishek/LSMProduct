import {
	Box,
	Button,
	Center,
	Img,
	Input,
	Spinner,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { useQuery } from 'react-query';

import MediaAPI from '../../../utils/media';

interface Props {
	featuredImageId: number;
	register: any;
}

const ImageBox: React.FC<Props> = (props) => {
	const { featuredImageId, register } = props;
	if (!featuredImageId) {
		return <> </>;
	}
	const imageAPI = new MediaAPI();
	const imageQuery = useQuery(
		[`image${featuredImageId}`, featuredImageId],
		() => imageAPI.get(featuredImageId)
	);

	const [imageId, setImageId] = useState<any>(featuredImageId);

	console.log(imageId);
	return (
		<>
			<Input
				type="hidden"
				defaultValue={imageId}
				name="featured_image"
				ref={register}
			/>
			{imageQuery.isLoading ? (
				<Center border="1px" borderColor="gray.100" h="36" overflow="hidden">
					<Spinner />
				</Center>
			) : (
				<Stack direction="column" spacing="4">
					<Box border="1px" borderColor="gray.100" h="36" overflow="hidden">
						<Img
							src={imageQuery.data.media_details.sizes.medium.source_url}
							objectFit="cover"
							w="full"
						/>
					</Box>
					<Button colorScheme="red" variant="outline">
						{__('Remove featured Image', 'masteriyo')}
					</Button>
				</Stack>
			)}
		</>
	);
};

export default ImageBox;
