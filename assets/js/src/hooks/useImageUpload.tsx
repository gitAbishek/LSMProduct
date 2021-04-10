import { Box, Flex, Icon, Text, useToast } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import Dropzone from 'react-dropzone';
import { BiPlus } from 'react-icons/bi';
import { useMutation } from 'react-query';

import MediaAPI from '../utils/media';

interface ImageUploadProps {
	uploadOn?: 'click' | 'drop';
}

const useImageUpload = () => {
	const toast = useToast();
	const imageAPi = new MediaAPI();
	const [preview, setPreview] = useState<any>(null);
	const [uploadedMediaData, setUploadedMediaData] = useState<any>(null);
	const uploadMedia = useMutation((image: any) => imageAPi.store(image));
	const deleteMedia = useMutation((id: any) => imageAPi.delete(id));
	const uploadImage = (file: any) => {
		let formData = new FormData();
		formData.append('file', file);

		uploadMedia.mutate(formData, {
			onSuccess: (mediaData) => {
				setUploadedMediaData(mediaData);
				setPreview(mediaData.source_url);
			},
		});
	};

	const deleteImage = () => {
		console.log(uploadedMediaData.id);
		deleteMedia.mutate(uploadedMediaData.id, {
			onSuccess: (mediaData) => {
				console.log(mediaData);
				setUploadedMediaData(null);
				setPreview(null);
			},
		});
	};
	const onDrop = (uploadOn: string, acceptedFiles: any) => {
		if (acceptedFiles.length) {
			if (uploadOn == 'drop') {
				setPreview(URL.createObjectURL(acceptedFiles[0]));
				uploadImage(acceptedFiles[0]);
			}
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

	const ImageUpload: React.FC<ImageUploadProps> = (props) => {
		const { uploadOn = 'drop' } = props;

		return (
			<Dropzone
				onDrop={(acceptedFiles) => onDrop(uploadOn, acceptedFiles)}
				accept="image/jpeg, image/png">
				{({
					getRootProps,
					getInputProps,
					isDragAccept,
					isDragReject,
					isDragActive,
				}) => (
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
			</Dropzone>
		);
	};

	return {
		preview,
		ImageUpload,
		deleteImage,
		uploadedMediaData,
		isUploading: uploadMedia.isLoading,
		isDeleting: deleteMedia.isLoading,
	};
};

export default useImageUpload;
