import {
	Box,
	Button,
	Center,
	Flex,
	Icon,
	Img,
	Input,
	Spinner,
	Stack,
	Text,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useEffect, useState } from 'react';
import { useDropzone } from 'react-dropzone';
import { BiPlus } from 'react-icons/bi';
import { useMutation, useQuery } from 'react-query';

import MediaAPI from '../../utils/media';

interface Props {
	mediaId?: any;
	name: string;
	register: any;
	setValue: any;
}

const ImageUpload: React.FC<Props> = (props) => {
	const { mediaId, name, register, setValue } = props;
	const toast = useToast();
	const [preview, setPreview] = useState<any>(null);
	const [imageId, setImageId] = useState<any>(mediaId || '0');

	const imageAPi = new MediaAPI();

	const uploadMedia = useMutation((image: any) => imageAPi.store(image));
	const deleteMedia = useMutation((id: any) => imageAPi.delete(id));

	const imageQuery = useQuery(
		[`image${mediaId}`, mediaId],
		() => imageAPi.get(mediaId),
		{
			enabled: !!mediaId,
			onSuccess: (data) => {
				setImageId(data.id);
				setPreview(data.source_url);
			},
		}
	);

	const uploadImage = (file: any) => {
		let formData = new FormData();
		formData.append('file', file);

		uploadMedia.mutate(formData, {
			onSuccess: (mediaData) => {
				setImageId(mediaData?.id);
				setPreview(mediaData?.source_url);
			},
		});
	};

	const deleteImage = (mediaId: any) => {
		deleteMedia.mutate(mediaId, {
			onSuccess: () => {
				setImageId(0);
				setPreview(null);
			},
		});
	};

	const onDrop = (acceptedFiles: any) => {
		if (acceptedFiles.length) {
			setPreview(URL.createObjectURL(acceptedFiles[0]));
			uploadImage(acceptedFiles[0]);
		} else {
			toast({
				title: __('Please upload Valid Image files', 'masteriyo'),
				description: __(
					'Media files jpeg, png are only supported',
					'masteriyo'
				),
				status: 'error',
				isClosable: true,
			});
			return;
		}
	};
	const {
		getRootProps,
		getInputProps,
		isDragAccept,
		isDragReject,
		isDragActive,
	} = useDropzone({
		accept: 'image/jpeg, image/png',
		onDrop: onDrop,
	});

	const isLoading = imageQuery?.isLoading || uploadMedia.isLoading;

	useEffect(() => {
		setValue(name, imageId);
	}, [imageId]);

	return (
		<>
			<Input type="hidden" {...register(name)} />
			{isLoading && (
				<Box>
					<Center border="1px" borderColor="gray.100" h="36" overflow="hidden">
						<Spinner />
					</Center>
				</Box>
			)}

			{!isLoading && preview && (
				<Stack direction="column" spacing="4">
					<Box border="1px" borderColor="gray.100" h="36" overflow="hidden">
						<Img src={preview} objectFit="cover" h="full" />
					</Box>
					<Button
						colorScheme="red"
						variant="outline"
						isLoading={deleteMedia?.isLoading}
						onClick={() => {
							deleteImage(imageId);
							setImageId(0);
						}}>
						{__('Remove featured Image', 'masteriyo')}
					</Button>
				</Stack>
			)}

			{!preview && (
				<Box
					transition="ease-in-out"
					border="2px"
					borderStyle="dashed"
					borderColor="gray.300"
					bg={isDragAccept ? 'green.50' : isDragReject ? 'red.50' : 'gray.50'}
					position="relative"
					h="36"
					{...getRootProps()}>
					<input {...getInputProps()} multiple={false} />

					<Flex
						align="center"
						justify="center"
						position="absolute"
						left="0"
						right="0"
						top="0"
						bottom="0">
						<Box>
							<Icon
								as={BiPlus}
								h="10"
								w="10"
								color={isDragActive ? 'blue.500' : 'gray.500'}
							/>
						</Box>
						<Text>{__('Upload an Image here', 'masteriyo')}</Text>
					</Flex>
				</Box>
			)}
		</>
	);
};

export default ImageUpload;
