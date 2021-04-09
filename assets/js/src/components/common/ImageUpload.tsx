import 'cropperjs/dist/cropper.css';

import { Box, Flex, Icon, Text, useToast } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import { useDropzone } from 'react-dropzone';
import { BiPlus } from 'react-icons/bi';

interface Props {
	setFile: any;
	setPreview: any;
}

const ImageUpload: React.FC<Props> = (props) => {
	const { setFile, setPreview } = props;
	const toast = useToast();

	const {
		getRootProps,
		getInputProps,
		isDragAccept,
		isDragReject,
		isDragActive,
	} = useDropzone({
		accept: 'image/jpeg, image/png',
		onDrop: (acceptedFiles) => onDrop(acceptedFiles),
	});

	const onDrop = (acceptedFiles: any) => {
		if (acceptedFiles.length) {
			setFile(acceptedFiles[0]);
			setPreview(URL.createObjectURL(acceptedFiles[0]));
		} else {
			toast({
				title: __('Please upload Image files', 'masteriyo'),
				description: __(
					'Media files jpeg, png are only supported',
					'masteriyo'
				),
				status: 'error',
				isClosable: true,
			});
		}
	};

	return (
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
	);
};

export default ImageUpload;
