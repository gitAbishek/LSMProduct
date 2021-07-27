import {
	Button,
	Center,
	Progress,
	Stack,
	Text,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useDropzone } from 'react-dropzone';
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
	const { getRootProps, getInputProps, isDragAccept, isDragReject } =
		useDropzone({
			accept: 'image/jpeg, image/png',
			onDrop: onDrop,
		});

	return (
		<>
			<Center
				bg={isDragAccept ? 'green.50' : isDragReject ? 'red.50' : '#f8f8f8'}
				border="2px dashed"
				borderColor="gray.200"
				borderRadius="sm"
				transition="ease-in-out"
				textAlign="center"
				position="relative"
				h="full"
				{...getRootProps()}>
				<input {...getInputProps()} multiple={false} />

				{uploadMedia.isLoading ? (
					<Progress hasStripe size="xs" />
				) : (
					<Stack direction="column">
						<Text>{__('Drop Files To Upload', 'masteriyo')}</Text>
						<Text fontSize="xs">{__('Or', 'masteriyo')}</Text>
						<Button variant="outline" colorScheme="blue">
							{__('Select Files', 'masteriyo')}
						</Button>
					</Stack>
				)}
			</Center>
		</>
	);
};

export default ImageUpload;
