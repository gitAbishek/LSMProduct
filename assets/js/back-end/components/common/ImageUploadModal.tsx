import {
	Box,
	Button,
	ButtonGroup,
	Center,
	HStack,
	Icon,
	IconButton,
	Image,
	Input,
	Modal,
	ModalBody,
	ModalCloseButton,
	ModalContent,
	ModalFooter,
	ModalHeader,
	ModalOverlay,
	Spinner,
	Stack,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	Text,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { useDropzone } from 'react-dropzone';
import { BiCheck, BiChevronLeft, BiChevronRight } from 'react-icons/bi';
import { useMutation, useQuery } from 'react-query';
import { MediaSchema } from '../../schemas';
import MediaAPI from '../../utils/media';

interface Props {
	isOpen: boolean;
	onClose: any;
	onComplete?: any;
	get?: 'id' | 'url';
	selected?: number;
	title?: string;
	addButtonText?: string;
	mediaType?: 'all' | 'video' | 'image';
}

//@ts-ignore
const homeURL = window._MASTERIYO_.home_url;

const ImageUploadModal: React.FC<Props> = (props) => {
	const {
		isOpen,
		onClose,
		onComplete,
		get,
		selected,
		title,
		addButtonText,
		mediaType = 'image',
	} = props;
	const toast = useToast();
	const imageAPi = new MediaAPI();
	const [selectedImage, setSelectedImage] = useState<any>(selected);
	const [imageUrl, setImageUrl] = useState<any>(null);
	const [tabIndex, setTabIndex] = useState(0);
	const [imageTitle, setImageTitle] = useState<string>('');

	const [page, setPage] = useState(1);
	const imagesQuery = useQuery(['medias', page, mediaType], () =>
		imageAPi.list({ page: page, media_type: mediaType })
	);

	const uploadMedia = useMutation((image: any) => imageAPi.store(image));

	const prevPage = page > 1 ? page - 1 : 1;
	const nextPage = page + 1;

	const getImageTitle = (imageTitle: string, mimeType: string) => {
		// Get only type. Example: 'video/mp4' -> 'mp4', 'image/png' -> 'png'.
		const mediaType = mimeType.substring(
			mimeType.lastIndexOf('/') + 1,
			mimeType.length
		);
		const title = imageTitle + '.' + mediaType;

		return title;
	};

	const onUpload = (file: any) => {
		setTabIndex(1);
		let formData = new FormData();
		formData.append('file', file);

		uploadMedia.mutate(formData, {
			onSuccess: (data: MediaSchema) => {
				imagesQuery.refetch();
				setSelectedImage(data.id);
				setImageUrl(data.source_url);
			},
		});
	};

	const onDrop = (acceptedFiles: any) => {
		if (acceptedFiles.length) {
			onUpload(acceptedFiles[0]);
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
			accept:
				'image/jpeg, image/png, video/mp4, video/m4v, video/webm, video/ogv, video/wmv, video/flv',
			onDrop: onDrop,
		});

	return (
		<Modal
			size="fullSpacing"
			isOpen={isOpen}
			onClose={onClose}
			isCentered
			closeOnEsc={false}>
			<ModalOverlay />
			<ModalContent>
				<ModalHeader
					bg="gray.800"
					borderTopRightRadius="xs"
					borderTopLeftRadius="xs"
					color="white"
					fontSize="sm">
					{title || __('Media Manager', 'masteriyo')}
				</ModalHeader>
				<ModalCloseButton color="white" />
				<ModalBody py="6" p="0">
					<Tabs onChange={(index) => setTabIndex(index)} index={tabIndex}>
						<TabList>
							<Tab fontSize="sm" px="0" py="3" mx="4">
								{__('Upload files')}
							</Tab>
							<Tab fontSize="sm" px="0" py="3" mx="4">
								{__('Media Library')}
							</Tab>
						</TabList>
						<TabPanels flex="1">
							<TabPanel>
								<Center
									bg={
										isDragAccept
											? 'green.50'
											: isDragReject
											? 'red.50'
											: '#f8f8f8'
									}
									border="2px dashed"
									borderColor="gray.200"
									borderRadius="sm"
									transition="ease-in-out"
									textAlign="center"
									position="relative"
									h="180px"
									{...getRootProps()}>
									<input {...getInputProps()} multiple={false} />

									{uploadMedia.isLoading ? (
										<>
											<Spinner />
											<Text ml="3">
												{__('Uploading file, please wait...', 'masteriyo')}
											</Text>
										</>
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
							</TabPanel>
							<TabPanel>
								{imagesQuery.isLoading && (
									<Center mt="4"> Loading images...</Center>
								)}
								<Stack direction="row" flexWrap="wrap" spacing="2">
									{uploadMedia.isLoading && (
										<Center bg="gray.100" w="100px" h="100px">
											<Box>
												<Spinner />
											</Box>
										</Center>
									)}
									{imagesQuery.isSuccess &&
										imagesQuery.data.data.map((image: MediaSchema) => {
											if (
												image.media_type === 'image' ||
												image.media_type === 'file'
											) {
												return (
													<Box
														key={image.id}
														w="100px"
														h="100px"
														position="relative">
														<Image
															border="3px solid"
															cursor="pointer"
															borderColor={
																selectedImage === image.id
																	? 'blue.500'
																	: 'transparent'
															}
															onClick={() => {
																setSelectedImage(image.id);
																setImageUrl(image.source_url);
																setImageTitle(
																	getImageTitle(
																		image.title.rendered,
																		image.mime_type
																	)
																);
															}}
															onDoubleClick={() =>
																window.open(image.source_url, '_blank')
															}
															src={
																image.media_type === 'image'
																	? image.source_url
																	: homeURL +
																	  '/wp-includes/images/media/video.png'
															}
															objectFit="cover"
															h="full"
															w="full"
														/>
														{selectedImage === image.id && (
															<Icon
																as={BiCheck}
																pos="absolute"
																right="0"
																top="0"
																fontSize="xl"
																color="white"
																shadow="md"
																bg="blue.500"
															/>
														)}
													</Box>
												);
											}
										})}
								</Stack>
								{imagesQuery.isSuccess && (
									<Stack
										mt="8"
										w="full"
										direction="row"
										justifyContent="space-between"
										pb="4"
										fontSize="sm">
										<Text>{imageTitle}</Text>
										<HStack>
											<IconButton
												shadow="none"
												rounded="sm"
												_hover={{ bg: 'blue.500', color: 'white' }}
												icon={<Icon fontSize="xl" as={BiChevronLeft} />}
												size="sm"
												aria-label="Previous page"
												onClick={() => {
													setPage(prevPage);
												}}
												disabled={page < 2}
											/>
											<Input
												size="sm"
												w="10"
												type="number"
												min={1}
												value={page}
												isReadOnly={true}
											/>
											<IconButton
												shadow="none"
												rounded="sm"
												_hover={{ bg: 'blue.500', color: 'white' }}
												icon={<Icon fontSize="xl" as={BiChevronRight} />}
												aria-label="next page"
												size="sm"
												onClick={() => {
													setPage(nextPage);
												}}
												disabled={
													page ===
													parseInt(imagesQuery.data.headers['x-wp-totalpages'])
												}
											/>
										</HStack>
									</Stack>
								)}
							</TabPanel>
						</TabPanels>
					</Tabs>
				</ModalBody>
				<ModalFooter bg="gray.50" borderTop="1px" borderColor="gray.100">
					<ButtonGroup>
						<Button
							colorScheme="blue"
							onClick={() => {
								onComplete(get === 'url' ? imageUrl : selectedImage);
							}}>
							{addButtonText || __('Add Image', 'masteriyo')}
						</Button>
						<Button variant="outline" onClick={onClose}>
							{__('Cancel', 'masteriyo')}
						</Button>
					</ButtonGroup>
				</ModalFooter>
			</ModalContent>
		</Modal>
	);
};

export default ImageUploadModal;
