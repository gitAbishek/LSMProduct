import {
	Box,
	Button,
	ButtonGroup,
	Icon,
	Image,
	Modal,
	ModalBody,
	ModalCloseButton,
	ModalContent,
	ModalFooter,
	ModalHeader,
	ModalOverlay,
	Stack,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { BiCheck } from 'react-icons/bi';
import { useQuery } from 'react-query';
import { MediaSchema } from '../../schemas';
import MediaAPI from '../../utils/media';
import ImageUpload from './ImageUpload';

interface Props {
	isOpen: boolean;
	onClose: any;
	onSucces?: any;
}
const ImageUploadModal: React.FC<Props> = (props) => {
	const { isOpen, onClose, onSucces } = props;
	const [imageUrl, setImageUrl] = useState(null);
	const imageAPi = new MediaAPI();
	const imagesQuery = useQuery('medias', () => imageAPi.list());
	const [imageId, setImageId] = useState<number>();
	const [tabIndex, setTabIndex] = useState(0);

	return (
		<Modal size="fullSpacing" isOpen={isOpen} onClose={onClose} isCentered>
			<ModalOverlay />
			<ModalContent>
				<ModalHeader
					bg="gray.800"
					borderTopRightRadius="xs"
					borderTopLeftRadius="xs"
					color="white"
					fontSize="sm">
					{__('Media Manager', 'masteriyo')}
				</ModalHeader>
				<ModalCloseButton color="white" />
				<ModalBody py="6" p="0">
					<Tabs
						onChange={(index) => setTabIndex(index)}
						index={tabIndex}
						h="full"
						d="flex"
						flexDirection="column">
						<TabList>
							<Tab fontSize="sm" px="0" py="3" mx="4">
								{__('Upload files')}
							</Tab>
							<Tab fontSize="sm" px="0" py="3" mx="4">
								{__('Media Library')}
							</Tab>
						</TabList>
						<TabPanels flex="1">
							<TabPanel h="full">
								<ImageUpload onUploadSuccess={() => setTabIndex(1)} />
							</TabPanel>
							<TabPanel>
								{imagesQuery.isSuccess && (
									<Stack direction="row" flexWrap="wrap" spacing="2">
										{imagesQuery.data.map((image: MediaSchema) => (
											<Box
												key={image.id}
												w="140px"
												h="140px"
												position="relative">
												<Image
													border="3px solid"
													cursor="pointer"
													borderColor={
														imageId === image.id ? 'blue.500' : 'transparent'
													}
													onClick={() => {
														setImageId(image.id);
													}}
													src={image.source_url}
													objectFit="cover"
													h="full"
													w="full"
												/>
												{imageId === image.id && (
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
										))}
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
							onClick={() => onSucces(imageUrl)}
							isDisabled={!imageUrl}>
							{__('Add Image', 'masteriyo')}
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
