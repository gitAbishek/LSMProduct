import {
	Box,
	FormLabel,
	Icon,
	Popover,
	PopoverArrow,
	PopoverBody,
	PopoverContent,
	PopoverTrigger,
	Tooltip,
} from '@chakra-ui/react';
import React, { useEffect, useState } from 'react';
import { HexColorInput, HexColorPicker } from 'react-colorful';
import { useFormContext } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import { infoIconStyles } from '../../config/styles';
import FormControlTwoCol from './FormControlTwoCol';

interface Props {
	name: `${string}` | `${string}.${string}` | `${string}.${number}`;
	defaultColor: string | any;
	label: string;
	description: string;
}
const ColorInput: React.FC<Props> = (props) => {
	const { defaultColor, name, label, description } = props;
	const [color, setColor] = useState(defaultColor);
	const { register, setValue } = useFormContext();

	useEffect(() => {
		setValue(name, color);
	}, [color, setValue, name]);

	return (
		<FormControlTwoCol>
			<FormLabel>
				{label}
				<Tooltip label={description} hasArrow fontSize="xs">
					<Box as="span" sx={infoIconStyles}>
						<Icon as={BiInfoCircle} />
					</Box>
				</Tooltip>
			</FormLabel>
			<input type="hidden" {...register(name)} defaultValue={defaultColor} />
			<Box
				flex={'1'}
				sx={{
					'.color-input-field': {
						width: 'full',
						border: '1px solid',
						borderColor: 'gray.100',
						shadow: 'input',
						borderRadius: 'sm',
						px: '2',
						py: '2',
						ps: '9',
					},
				}}>
				<Popover placement="bottom-start">
					<PopoverTrigger>
						<Box pos="relative">
							<Box
								pos="absolute"
								w="20px"
								h="20px"
								bg={color}
								rounded="sm"
								top="10px"
								left="10px"
							/>
							<HexColorInput
								onChange={setColor}
								color={color}
								className="color-input-field"
							/>
						</Box>
					</PopoverTrigger>
					<PopoverContent w={'none'}>
						<PopoverArrow />
						<PopoverBody>
							<HexColorPicker onChange={setColor} color={color} />
						</PopoverBody>
					</PopoverContent>
				</Popover>
			</Box>
		</FormControlTwoCol>
	);
};

export default ColorInput;
