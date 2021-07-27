import {
	Box,
	Center,
	Flex,
	Icon,
	Spinner,
	Text,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useDropzone } from 'react-dropzone';
import { BiPlus } from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';
import MediaAPI from '../../utils/media';

interface Props {
	mediaId?: any;
	name?: string;
	register?: any;
	setValue?: any;
	onUploadSuccess?: any;
	onImageDelete?: any;
}

const ImageUpload: React.FC<Props> = (props) => {
	const { mediaId, name, register, setValue, onUploadSuccess } = props;
	const toast = useToast();
	const queryClient = useQueryClient();

	const imageAPi = new MediaAPI();

	const uploadMedia = useMutation((image: any) => imageAPi.store(image));

	const uploadImage = (file: any) => {
		let formData = new FormData();
		formData.append('file', file);

		uploadMedia.mutate(formData, {
			onSuccess: () => {
				queryClient.invalidateQueries('medias');
			},
		});
	};

	const onDrop = (acceptedFiles: any) => {
		if (acceptedFiles.length) {
			uploadImage(acceptedFiles[0]);
			onUploadSuccess && onUploadSuccess;
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

	return (
		<>
			{uploadMedia.isLoading && (
				<Box>
					<Center border="1px" borderColor="gray.100" h="36" overflow="hidden">
						<Spinner />
					</Center>
				</Box>
			)}

			<Box
				transition="ease-in-out"
				border="2px"
				borderStyle="dashed"
				borderColor="gray.300"
				bg={isDragAccept ? 'green.50' : isDragReject ? 'red.50' : 'gray.50'}
				position="relative"
				h="full"
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
		</>
	);
};

export default ImageUpload;
